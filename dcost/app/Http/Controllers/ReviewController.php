namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Kos;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $kos_id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'kos_id' => $kos_id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return redirect()->back()->with('success', 'Review berhasil ditambahkan!');
    }

    public function index($kos_id)
    {
        $kos = Kos::findOrFail($kos_id);
        $reviews = Review::where('kos_id', $kos_id)->latest()->get();

        return view('reviews.index', compact('kos', 'reviews'));
    }
}

