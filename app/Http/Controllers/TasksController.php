<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TasksController extends Controller
{
    public function index()
    {
        return view('tasks.index', [
            'categories' => Category::all(),
            'users' => User::all(),
            // 'task' => Task::all()
        ]);
    }

    public function store(Request $request)
    {
        Log::info('Request data: ', $request->all());

        DB::beginTransaction();

        try {
            // Validasi data
            $rules = [
                'name' => 'required|string|max:255',
                'list.*' => 'required|string|max:255',
                'categories.*' => 'required|string|exists:categories,name',
            ];

            // Tambahkan aturan validasi untuk username dan email jika diperlukan
            $user = User::where('name', $request->input('name'))->first();
            if (!$user) {
                $rules['username'] = 'required|string|max:255|unique:users';
                $rules['email'] = 'required|string|email|max:255|unique:users';
            } else {
                $rules['username'] = 'nullable|string|max:255|unique:users,username,' . $user->id;
                $rules['email'] = 'nullable|string|email|max:255|unique:users,email,' . $user->id;
            }

            $request->validate($rules);

            if (!$user) {
                // Jika tidak ada, buat pengguna baru
                $user = User::create([
                    'name' => $request->input('name'),
                    'username' => $request->input('username'),
                    'email' => $request->input('email'),
                ]);

                Log::info('User created: ', $user->toArray());
            }

            // Menyimpan data task
            foreach ($request->input('list') as $index => $listItem) {
                $category = Category::where('name', $request->input('categories')[$index])->firstOrFail();

                $task = Task::create([
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    'description' => $listItem,
                ]);

                Log::info('Task created: ', $task->toArray());
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Data berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error saat menyimpan data: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
            ]);

            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan, data gagal disimpan.']);
        }
    }

    public function displayTodoList()
    {
        $tasks = Task::latest()->take(2)->get();

        // Mengambil semua kategori
        $categories = Category::all();

        return response()->json([
            'tasks' => $tasks,
            'categories' => $categories
        ]);
    }

    public function fetchTodoList(Request $request)
    {
        $tasks = Task::where('user_id', $request->user_id)->get();
        $categories = Category::all(); // Atau sesuaikan dengan kebutuhan

        return response()->json([
            'tasks' => $tasks,
            'categories' => $categories
        ]);
    }


    public function checkUser(Request $request)
    {
        $user = User::where('name', $request->name)->first();

        if ($user) {
            return response()->json([
                'exists' => true,
                'user' => $user
            ]);
        } else {
            return response()->json([
                'exists' => false
            ]);
        }
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['success' => true, 'message' => 'Task deleted successfully.']);
    }


}
