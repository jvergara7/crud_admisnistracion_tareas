<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $completedTasksCount = Task::where('status', 'Completada')->count();
        $pendingTasksCount = Task::where('status', 'Por hacer')->count();

        $tasks = Task::latest()->get();

        return Inertia::render('Task/Index', compact('completedTasksCount', 'pendingTasksCount', 'tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Task/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:Por hacer,En progreso,Completada',
        ]);

        $task = Task::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'status' => $validatedData['status'],
            'user_id' => Auth::user()->getAuthIdentifier()
        ]);

        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $originalName = $file->getClientOriginalName();
                $path = $file->storeAs('files', $originalName); // Guarda cada archivo en la carpeta "storage/app/files"
                $task->taskFiles()->create(['file' => $originalName]);
            }
        }

        return redirect()->route('task.index')->with('success', 'Tarea creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $files = $task->taskFiles()->get();
        return Inertia::render('Task/Show', [
            'task' => $task,
            'files' => $files
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $files = $task->taskFiles()->get();
        return Inertia::render('Task/Edit', [
            'task' => $task,
            'files' => $files
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:Por hacer,En progreso,Completada'
        ]);

        $task->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'status' => $validatedData['status'],
            ]);

        // Obtener los nombres de los documentos actuales asociados a la tarea
        $currentDocumentNames = $task->taskFiles()->pluck('file')->toArray();

        // Subir los archivos nuevos y actualizar la relación con la tarea (si es necesario)
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $originalName = $file->getClientOriginalName();

                // Verificar si el documento ya existe antes de guardarlo nuevamente
                if (!in_array($originalName, $currentDocumentNames)) {
                    $originalName = $file->getClientOriginalName();
                    $path = $file->storeAs('files', $originalName);
                                        // Crear un nuevo registro en la tabla "task_files"
                    TaskFile::create([
                        'task_id' => $task->id,
                        'file' => $originalName
                    ]);
                }
            }
        }

        // Redireccionar o retornar una respuesta adecuada, por ejemplo:
        return redirect()->route('task.index')->with('success', 'Tarea actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $fileNames = $task->taskFiles()->pluck('file')->toArray();

        // Eliminar los registros de archivos asociados a la tarea
        $task->taskFiles()->delete();

        // Eliminar físicamente los archivos de la carpeta de almacenamiento
        foreach ($fileNames as $fileName) {
            $filePath = storage_path('app/files/' . $fileName);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
        $task->delete();
        return redirect()->route('task.index')->with('status', 'Nota eliminada');
    }

    public function report()
    {
        $userId = Auth::id();
        $tasks = Task::where('user_id', $userId)->get();

        return Inertia::render('Task/Report', [
            'tasks' => $tasks,
        ]);
    }

    public function downloadFile($fileId)
    {
        $file = TaskFile::findOrFail($fileId);

        $filePath = storage_path('app/files/' . $file->file);

        if (file_exists($filePath)) {
            return response()->download($filePath, $file->file);
        }

        abort(404);
    }
    public function deleteFile($id)
    {
        $file = TaskFile::findOrFail($id);
        Storage::delete('files/' . $file->name);
        $file->delete();

        return response()->json(['message' => 'Archivo eliminado exitosamente']);
    }
    public function completeTask(Task $task)
    {
        $task->update(['status' => 'Completada']);
        return redirect()->route('task.index')->with('success', 'Tarea completada exitosamente.');
    }
}
