namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index(Request $request) {
        return $request->user()->tasks()->latest()->get();
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'completed' => 'boolean'
        ]);
        return $request->user()->tasks()->create($request->all());
    }

    public function update(Request $request, Task $task) {
        abort_unless($task->user_id === $request->user()->id, 403);
        $task->update($request->all());
        return $task;
    }

    public function destroy(Request $request, Task $task) {
        abort_unless($task->user_id === $request->user()->id, 403);
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }
}