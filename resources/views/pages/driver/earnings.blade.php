@extends('layouts.app')

@push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')
<div class="main-layout">
    {{-- Driver Navigation Sidebar --}}
    <aside class="mlay-side">
        <nav class="driver-nav">
            <a href="{{ route('driver.dashboard') }}" class="driver-nav-link">
                <i class="fa-solid fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="{{ route('driver.rides') }}" class="driver-nav-link">
                <i class="fa-solid fa-car"></i> Ride Management
            </a>
            <a href="{{ route('driver.earnings') }}" class="driver-nav-link active">
                <i class="fa-solid fa-dollar-sign"></i> Earnings
            </a>
            <a href="{{ route('user.view', $user) }}" class="driver-nav-link">
                <i class="fa-solid fa-user-gear"></i> My Profile
            </a>
        </nav>
    </aside>

    {{-- Main Content --}}
    <main class="main-content">
        <div class="earnings-layout">
            {{-- Left Column - Transaction History --}}
            <div class="earnings-col-left">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Earnings History</h2>
                    </div>
                    <div class="card-body">
                        <div class="earnings-tabs">
                            <button class="tab-button active" id="tab-day" onclick="filterEarnings('today')">Today</button>
                            <button class="tab-button" id="tab-week" onclick="filterEarnings('week')">This Week</button>
                            <button class="tab-button" id="tab-month" onclick="filterEarnings('month')">This Month</button>
                        </div>

                        <div class="tab-content" id="content-display">
                            <h3 class="earnings-label" style="text-align: left;">
                                <span id="period-label">Today's</span> Total: 
                                <span class="earnings-amount" style="font-size: 2rem;" id="period-total">₱{{ number_format($todayEarnings, 2) }}</span>
                            </h3>

                            <ul class="transaction-list" id="transaction-list">
                                @forelse($recentTransactions as $transaction)
                                    <li class="transaction-item" data-date="{{ $transaction->created_at->format('Y-m-d') }}">
                                        <div class="transaction-details">
                                            <span class="transaction-icon ride">
                                                <i class="fa-solid fa-car"></i>
                                            </span>
                                            <div class="transaction-info">
                                                <p>Ride Fare</p>
                                                <span>Passenger: {{ $transaction->passenger->getFullName() }}</span>
                                                <span style="display: block; font-size: 0.8rem; color: var(--text-light);">
                                                    {{ $transaction->created_at->format('M d, Y g:i A') }}
                                                </span>
                                            </div>
                                        </div>
                                        <span class="transaction-amount">+ ₱{{ number_format($transaction->price, 2) }}</span>
                                    </li>
                                @empty
                                    <li class="no-transactions">
                                        <p>No transactions yet.</p>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - Summary Cards --}}
            <div class="earnings-col-right">
                {{-- Total Earnings Card --}}
                <div class="card earnings-summary-card">
                    <h3 class="earnings-label">Total Earnings (All Time)</h3>
                    <p class="earnings-amount" id="total-earnings">₱{{ number_format($totalEarnings, 2) }}</p>
                    <p class="payout-info">From {{ $completedRidesCount }} completed rides</p>
                </div>

                {{-- Weekly Earnings Card --}}
                <div class="card earnings-summary-card">
                    <h3 class="earnings-label">This Week's Earnings</h3>
                    <p class="earnings-amount" style="color: var(--secondary);">₱{{ number_format($weekEarnings, 2) }}</p>
                    <p class="payout-info">{{ $weekRidesCount }} rides this week</p>
                </div>

                {{-- Monthly Earnings Card --}}
                <div class="card earnings-summary-card">
                    <h3 class="earnings-label">This Month's Earnings</h3>
                    <p class="earnings-amount" style="color: var(--primary);">₱{{ number_format($monthEarnings, 2) }}</p>
                    <p class="payout-info">{{ $monthRidesCount }} rides this month</p>
                </div>

                {{-- Average Per Ride Card --}}
                <div class="card earnings-summary-card">
                    <h3 class="earnings-label">Average Per Ride</h3>
                    <p class="earnings-amount" style="color: var(--accent);">
                        ₱{{ $completedRidesCount > 0 ? number_format($totalEarnings / $completedRidesCount, 2) : '0.00' }}
                    </p>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    const transactionsData = @json($recentTransactions);

    function filterEarnings(period) {
        const now = new Date();
        const tabs = document.querySelectorAll('.tab-button');
        tabs.forEach(tab => tab.classList.remove('active'));

        let filteredTransactions = [];
        let totalAmount = 0;
        let periodLabel = '';

        if (period === 'today') {
            document.getElementById('tab-day').classList.add('active');
            const today = now.toISOString().split('T')[0];
            filteredTransactions = transactionsData.filter(t => t.created_at.startsWith(today));
            totalAmount = {{ $todayEarnings }};
            periodLabel = "Today's";
        } else if (period === 'week') {
            document.getElementById('tab-week').classList.add('active');
            const weekAgo = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
            filteredTransactions = transactionsData.filter(t => new Date(t.created_at) >= weekAgo);
            totalAmount = {{ $weekEarnings }};
            periodLabel = "This Week's";
        } else if (period === 'month') {
            document.getElementById('tab-month').classList.add('active');
            const monthAgo = new Date(now.getFullYear(), now.getMonth(), 1);
            filteredTransactions = transactionsData.filter(t => new Date(t.created_at) >= monthAgo);
            totalAmount = {{ $monthEarnings }};
            periodLabel = "This Month's";
        }

        // Update display
        document.getElementById('period-label').textContent = periodLabel;
        document.getElementById('period-total').textContent = '₱' + totalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

        // Update transaction list
        const transactionList = document.getElementById('transaction-list');
        if (filteredTransactions.length === 0) {
            transactionList.innerHTML = '<li class="no-transactions"><p>No transactions for this period.</p></li>';
        } else {
            transactionList.innerHTML = filteredTransactions.map(t => `
                <li class="transaction-item">
                    <div class="transaction-details">
                        <span class="transaction-icon ride">
                            <i class="fa-solid fa-car"></i>
                        </span>
                        <div class="transaction-info">
                            <p>Ride Fare</p>
                            <span>Passenger: ${t.passenger_name}</span>
                            <span style="display: block; font-size: 0.8rem; color: var(--text-light);">
                                ${new Date(t.created_at).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' })}
                            </span>
                        </div>
                    </div>
                    <span class="transaction-amount">+ ₱${parseFloat(t.price).toFixed(2)}</span>
                </li>
            `).join('');
        }
    }
</script>
@endpush
