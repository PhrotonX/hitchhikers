<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use App\Models\Driver;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $this->authorize('create', Auth::user());
        if(Auth::user()->getDriverAccount() != null){
            abort(403);
        }

        // return response()->json([
        //     'redirect' => 'pages.driver.enroll',
        // ]);
        return view('pages.driver.enroll');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDriverRequest $request): RedirectResponse
    {
        Log::debug('DriverController::onStore()');
        $this->onStore($request);

        Log::debug('Data saved');
        return redirect()->route('home', [
            'status' => __('string.driving_program_enrollment_success'),
        ]);
    }

    public function storeAPI(StoreDriverRequest $request): JsonResponse
    {
        $this->onStore($request);

        return response()->json([
            'redirect' => 'home',
            'status' => __('string.driving_program_enrollment_success'),
        ]);
    }

    protected function onStore(StoreDriverRequest $request){
        Log::debug('DriverController::onStore()');
        $validated = $request->validated();

        Log::debug('DriverController::onStore()');

        $driver = new Driver();
        $driver->fill($validated);
        $driver->user_id = Auth::user()->id;
        
        $driver->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Driver $driver)
    {
        $this->onEdit($driver);

        return view('pages.driver.edit', [
            'driverAccount' => $driver,
        ]);
    }

    public function editAPI(Driver $driver)
    {
        $this->onEdit($driver);

        return response()->json([
            'driverAccount' => $driver,
            'redirect' => 'pages.driver.edit',
        ]);
    }

    protected function onEdit(Driver $driver){
        $this->authorize('update', $driver);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDriverRequest $request, Driver $driver): RedirectResponse
    {
        $this->onUpdate($request, $driver);

        return redirect()->route('settings', [
            'status' => 'string.driver_edit_success',
        ]);
    }

    public function updateAPI(UpdateDriverRequest $request, Driver $driver): JsonResponse
    {
        $this->onUpdate($request, $driver);

        return response()->json([
            'status' => 'string.driver_edit_success',
        ]);
    }

    protected function onUpdate(UpdateDriverRequest $request, Driver $driver){
        $validated = $request->validated();

        $driver->fill($validated);
        
        $driver->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        $this->authorize('delete', $driver);

        $this->onDestroy($driver);

        return back()->with('status', 'account-deleted');
    }

    protected function onDestroy(Driver $driver){
        $driver->delete();
    }

    /**
     * Display driver dashboard with active ride and pending requests
     */
    public function dashboard()
    {
        $user = Auth::user();
        $driverAccount = $user->getDriverAccount();

        if (!$driverAccount) {
            return redirect()->route('home')->with('error', 'You must enroll as a driver first.');
        }

        // Get the driver's vehicle (first active vehicle)
        $vehicle = $user->getRides()->first()?->vehicle;

        // Get active ride (approved or ongoing ride)
        $activeRide = $user->getRides()
            ->whereIn('status', ['approved', 'ongoing'])
            ->with(['destinations', 'passenger'])
            ->first();

        // Get pending ride requests for all driver's available rides
        $rideRequests = \App\Models\RideRequest::whereHas('ride', function($query) use ($user) {
                $query->where('driver_id', $user->id)
                      ->where('status', 'available');
            })
            ->where('status', 'pending')
            ->with(['passenger', 'ride.destinations'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('pages.driver.dashboard', compact('user', 'driverAccount', 'vehicle', 'activeRide', 'rideRequests'));
    }

    /**
     * Display driver earnings page
     */
    public function earnings()
    {
        $user = Auth::user();
        $driverAccount = $user->getDriverAccount();

        if (!$driverAccount) {
            return redirect()->route('home')->with('error', 'You must enroll as a driver first.');
        }

        // Get all completed ride requests for this driver
        $completedRequests = \App\Models\RideRequest::whereHas('ride', function($query) use ($user) {
                $query->where('driver_id', $user->id);
            })
            ->where('status', 'approved')
            ->with(['passenger'])
            ->get();

        // Calculate earnings
        $totalEarnings = $completedRequests->sum('price');
        $completedRidesCount = $completedRequests->count();

        // Today's earnings
        $todayEarnings = $completedRequests->filter(function($request) {
            return $request->created_at->isToday();
        })->sum('price');

        // This week's earnings
        $weekEarnings = $completedRequests->filter(function($request) {
            return $request->created_at->isCurrentWeek();
        })->sum('price');
        $weekRidesCount = $completedRequests->filter(function($request) {
            return $request->created_at->isCurrentWeek();
        })->count();

        // This month's earnings
        $monthEarnings = $completedRequests->filter(function($request) {
            return $request->created_at->isCurrentMonth();
        })->sum('price');
        $monthRidesCount = $completedRequests->filter(function($request) {
            return $request->created_at->isCurrentMonth();
        })->count();

        // Recent transactions (last 30 days)
        $recentTransactions = $completedRequests->filter(function($request) {
            return $request->created_at->greaterThanOrEqualTo(now()->subDays(30));
        })->map(function($request) {
            return [
                'created_at' => $request->created_at->toISOString(),
                'price' => $request->price,
                'passenger_name' => $request->passenger->getFullName(),
            ];
        })->values();

        return view('pages.driver.earnings', compact(
            'user',
            'totalEarnings',
            'todayEarnings',
            'weekEarnings',
            'monthEarnings',
            'completedRidesCount',
            'weekRidesCount',
            'monthRidesCount',
            'recentTransactions'
        ));
    }

    /**
     * Display driver rides management page
     */
    public function rides()
    {
        $user = Auth::user();
        $driverAccount = $user->getDriverAccount();

        if (!$driverAccount) {
            return redirect()->route('home')->with('error', 'You must enroll as a driver first.');
        }

        // Get all rides for this driver
        $rides = $user->getRides()
            ->with(['vehicle', 'destinations', 'rideRequests.passenger'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.driver.rides', compact('user', 'rides'));
    }
}

