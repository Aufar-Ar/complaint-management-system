<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician Dashboard - IT CMS</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dropdown.css') }}">
    <style>
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
            margin-bottom: 48px;
        }

        .stat-card {
            background: #FFFFFF;
            border: 1px solid var(--border-strong);
            border-radius: var(--rounded-base);
            padding: 24px;
            display: flex;
            flex-direction: column;
            box-shadow: none;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-color: var(--primary);
        }
        .stat-card.active {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(229, 9, 19, 0.2);
        }

        .stat-value {
            font-size: 48px;
            font-weight: 700;
            font-family: var(--font-display);
            margin-bottom: 4px;
            color: var(--text-primary);
            line-height: 1;
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .list-container {
            background: #FFFFFF;
            border: 1px solid var(--border-strong);
            border-radius: var(--rounded-base);
            overflow-x: auto;
            margin-bottom: 64px;
            box-shadow: none;
        }

        .list-header {
            display: grid;
            grid-template-columns: 80px 120px 1fr 1fr 120px 120px;
            gap: 16px;
            align-items: center;
            padding: 12px 24px;
            background: var(--background);
            border-bottom: 1px solid var(--border-strong);
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            min-width: 800px;
        }

        .list-row {
            display: grid;
            grid-template-columns: 80px 120px 1fr 1fr 120px 120px;
            gap: 16px;
            align-items: center;
            padding: 16px 24px;
            border-bottom: 1px solid var(--border);
            transition: background 0.2s;
            cursor: pointer;
            min-width: 800px;
        }

        .list-row:last-child {
            border-bottom: none;
        }

        .list-row:hover {
            background: #F9FAFB;
        }

        .col-id {
            font-family: var(--font-code);
            font-size: 13px;
            color: var(--text-secondary);
        }

        .col-cat {
        }

        .col-emp {
            font-weight: 500;
        }

        .col-tech {
            color: var(--text-secondary);
        }

        .col-status {
            text-align: right;
        }

        .col-date {
            text-align: right;
            font-size: 13px;
            color: var(--text-secondary);
        }
    </style>
</head>

<body>
    <!-- Genesis Sticky Nav -->
    <div class="top-nav">
        <h1>IT Workspace <span
                style="font-size:12px; font-weight:600; color:var(--primary); background:#EEF2FF; padding:4px 8px; border-radius:var(--rounded-sm); margin-left:8px;">Technician</span>
        </h1>
        <div style="display: flex; align-items: center;">
            <!-- User Menu -->
            <div class="user-menu-wrapper">
                <button class="user-menu-btn" onclick="document.getElementById('userDropdown').classList.toggle('show')">
                    <span class="user-name">{{ auth()->user()->username }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px;"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div id="userDropdown" class="user-dropdown">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="user-dropdown-item">Log out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-container">

        @if(session('flash_success'))
            <div class="alert-success">{{ session('flash_success') }}</div>
        @endif

        @if(session('flash_error'))
            <div class="alert-error">{{ session('flash_error') }}</div>
        @endif

        <!-- HEADER BANNER UNTUK TECHNICIAN -->
        <div class="header" style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); padding: 32px; border-radius: 16px; margin-bottom: 32px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
            <div style="flex: 1; min-width: 250px; padding-right: 24px;">
                <h2 style="margin-top: 0; font-size: 28px; color: var(--text-primary); margin-bottom: 8px;">Welcome back, {{ auth()->user()->username }}!</h2>
                <p style="margin-bottom: 0; color: var(--text-secondary); font-size: 16px;">
                    There are <strong>{{ $stats->pending }}</strong> new tickets waiting in the queue.
                </p>
            </div>
            
            <div style="flex-basis: 200px; display: flex; justify-content: flex-end;">
                <!-- Mengambil gambar dari public/assets/images/server.svg -->
                <img src="{{ asset('assets/images/fix.svg') }}" alt="Server and Network" style="width: 100%; max-width: 180px; height: auto;">
            </div>
        </div>

        <!-- 1. System Overview Stats -->
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 24px;">
            <h2 class="header-title" style="font-size: 24px; margin: 0;">System Overview</h2>
            @if($active_filter)
                <a href="{{ route('technician.dashboard') }}" class="button-secondary">Clear Filter</a>
            @endif
        </div>

        <div class="stat-grid">
            <a href="{{ route('technician.dashboard', ['filter' => 'total']) }}" class="stat-card {{ $active_filter === 'total' ? 'active' : '' }}">
                <span class="stat-value">{{ $stats->total }}</span>
                <span class="stat-label">Total Tickets</span>
            </a>
            <a href="{{ route('technician.dashboard', ['filter' => 'pending']) }}" class="stat-card {{ $active_filter === 'pending' ? 'active' : '' }}">
                <span class="stat-value">{{ $stats->pending }}</span>
                <span class="stat-label" style="color: var(--warning);">Pending</span>
            </a>
            <a href="{{ route('technician.dashboard', ['filter' => 'in_progress']) }}" class="stat-card {{ $active_filter === 'in_progress' ? 'active' : '' }}">
                <span class="stat-value">{{ $stats->in_progress }}</span>
                <span class="stat-label" style="color: var(--primary);">In Progress</span>
            </a>
            <a href="{{ route('technician.dashboard', ['filter' => 'resolved']) }}" class="stat-card {{ $active_filter === 'resolved' ? 'active' : '' }}">
                <span class="stat-value">{{ $stats->resolved }}</span>
                <span class="stat-label" style="color: var(--success);">Resolved</span>
            </a>
        </div>

        @if($active_filter)
            <h2 class="header-title" style="font-size: 24px; margin-bottom: 24px;">
                Filtered Tickets ({{ ucwords(str_replace('_', ' ', $active_filter)) }})
            </h2>
            @if($filtered_tickets->isNotEmpty())
                <div class="list-container">
                    <div class="list-header">
                        <div class="col-id">Ticket #</div>
                        <div class="col-cat">Category</div>
                        <div class="col-emp">Employee</div>
                        <div class="col-tech">Assigned To</div>
                        <div class="col-date">Last Updated</div>
                        <div class="col-status">Status</div>
                    </div>
                    @foreach($filtered_tickets as $c)
                        <div class="list-row">
                            <div class="col-id">#{{ str_pad($c->id, 4, '0', STR_PAD_LEFT) }}</div>
                            <div class="col-cat"><span style="font-size:13px; font-weight:500;">{{ $c->category }}</span></div>
                            <div class="col-emp">{{ $c->employee_name }}</div>
                            <div class="col-tech">
                                @if($c->technician_name)
                                    {{ $c->technician_name }}
                                @else
                                    <span style="opacity:0.5;">Unassigned</span>
                                @endif
                            </div>
                            <div class="col-date">{{ \Carbon\Carbon::parse($c->updated_at)->format('M j, Y') }}</div>
                            <div class="col-status">
                                @if($c->status == 'pending')
                                    <span class="chip chip-warning">Pending</span>
                                @elseif($c->status == 'in_progress')
                                    <span class="chip chip-pink">In Progress</span>
                                @else
                                    <span class="chip chip-success">Resolved</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <p style="margin:0; font-size:14px;">No tickets found matching this filter.</p>
                </div>
            @endif

        @else

        <!-- 2. My Active Tickets Section -->
        <h2 class="header-title" style="font-size: 24px; margin-bottom: 24px;">My Active Tickets</h2>
        @if($my_tickets->isNotEmpty())
            <div class="card-grid" style="margin-bottom: 48px;">
                @foreach($my_tickets as $c)
                    <div class="card-base"
                        style="border: 2px solid var(--primary); box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);">
                        <div style="display:flex; justify-content:space-between; margin-bottom: 16px;">
                            <span class="chip chip-primary">{{ $c->category }}</span>
                            <span class="chip chip-pink" style="background:#E0E7FF; color:var(--primary);">In Progress</span>
                        </div>
                        <p style="font-size:15px; margin-bottom: 16px; color: var(--text-primary); flex-grow: 1;">
                            <strong>Reported by: {{ $c->employee_name }}</strong> 
                            <span style="color: var(--text-secondary); font-size: 13px;">(Location: {{ $c->floor }})</span><br><br>
                            {!! nl2br(e($c->description)) !!}
                        </p>
                        <div
                            style="background:var(--background); padding:12px; border-radius:var(--rounded-md); margin-bottom: 24px;">
                            <span
                                style="font-size:13px; color:var(--text-secondary); display:block; margin-bottom:4px;">Employee
                                Contact Number:</span>
                            <span
                                style="font-weight:600; font-family:var(--font-code);">{{ $c->phone_number }}</span>
                        </div>
                        @if($c->attachment)
                            <a href="{{ asset('storage/' . $c->attachment) }}" target="_blank"
                                style="font-size:14px; font-weight:500; color:var(--primary); text-decoration:none; display:block; margin-bottom:16px;">📎
                                View Attachment</a>
                        @endif

                        <form action="{{ route('technician.action') }}" method="POST" style="margin-top:auto;">
                            @csrf
                            <input type="hidden" name="ticket_id" value="{{ $c->id }}">
                            <input type="hidden" name="action" value="resolve">
                            <button type="submit" class="button-primary" style="width: 100%; background: var(--success);">Mark
                                as Resolved</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state" style="padding: 32px; margin-bottom: 48px;">
                <p style="margin:0; font-size:14px;">You have no active tickets. Claim one from the queue below.</p>
            </div>
        @endif

        <!-- 3. Open Queue Section -->
        <h2 class="header-title" style="font-size: 24px; margin-bottom: 24px;">Open Queue (Unclaimed)</h2>
        @if($open_tickets->isNotEmpty())
            <div class="card-grid" style="margin-bottom: 64px;">
                @foreach($open_tickets as $c)
                    <div class="card-base">
                        <div style="display:flex; justify-content:space-between; margin-bottom: 16px;">
                            <span class="chip chip-primary">{{ $c->category }}</span>
                            <span class="chip chip-warning">Pending</span>
                        </div>
                        <p style="font-size:15px; margin-bottom: 24px; color: var(--text-primary); flex-grow: 1;">
                            <span style="font-weight: 500; font-size: 14px; color: var(--text-secondary); display: block; margin-bottom: 8px;">Location: {{ $c->floor }}</span>
                            {!! nl2br(e($c->description)) !!}
                        </p>
                        @if($c->attachment)
                            <a href="{{ asset('storage/' . $c->attachment) }}" target="_blank"
                                style="font-size:14px; font-weight:500; color:var(--primary); text-decoration:none; display:block; margin-bottom:16px;">📎
                                View Attachment</a>
                        @endif

                        <div style="font-size:13px; color:var(--on-surface); margin-bottom: 16px;">
                            Submitted by {{ $c->employee_name }} on
                            {{ \Carbon\Carbon::parse($c->created_at)->format('M j, Y') }}
                        </div>

                        <form action="{{ route('technician.action') }}" method="POST" style="margin-top:auto;">
                            @csrf
                            <input type="hidden" name="ticket_id" value="{{ $c->id }}">
                            <input type="hidden" name="action" value="claim">
                            <button type="submit" class="button-primary" style="width: 100%;">Claim Ticket</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state" style="margin-bottom: 64px;">
                <p style="margin:0; font-size: 15px;">The queue is empty! Great job team.</p>
            </div>
        @endif

        <!-- 4. Recent Activity -->
        <h2 class="header-title" style="font-size: 24px; margin-bottom: 24px;">Recent Activity</h2>
        @if($recent_tickets->isNotEmpty())
            <div class="list-container">
                <div class="list-header">
                    <div class="col-id">Ticket #</div>
                    <div class="col-cat">Category</div>
                    <div class="col-emp">Employee</div>
                    <div class="col-tech">Assigned To</div>
                    <div class="col-date">Last Updated</div>
                    <div class="col-status">Status</div>
                </div>
                @foreach($recent_tickets as $c)
                    <div class="list-row">
                        <div class="col-id">#{{ str_pad($c->id, 4, '0', STR_PAD_LEFT) }}</div>
                        <div class="col-cat"><span
                                style="font-size:13px; font-weight:500;">{{ $c->category }}</span></div>
                        <div class="col-emp">{{ $c->employee_name }}</div>
                        <div class="col-tech">
                            @if($c->technician_name)
                                {{ $c->technician_name }}
                            @else
                                <span style="opacity:0.5;">Unassigned</span>
                            @endif
                        </div>
                        <div class="col-date">{{ \Carbon\Carbon::parse($c->updated_at)->format('M j, Y') }}</div>
                        <div class="col-status">
                            @if($c->status == 'pending')
                                <span class="chip chip-warning">Pending</span>
                            @elseif($c->status == 'in_progress')
                                <span class="chip chip-primary" style="background:#E0E7FF;">In Progress</span>
                            @else
                                <span class="chip chip-success">Resolved</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <p style="margin:0; font-size:14px;">No tickets have been submitted yet.</p>
            </div>
        @endif

        @endif {{-- End of if ($active_filter) else block --}}

    </div>
</body>

</html>