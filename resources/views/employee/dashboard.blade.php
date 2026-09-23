<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - IT CMS</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dropdown.css') }}">
</head>

<body>
    <!-- Genesis Sticky Nav -->
    <div class="top-nav">
        <h1>IT Workspace</h1>
        <div style="display: flex; align-items: center;">

            <!-- Notification Bell -->
            <div class="notif-wrapper">
                <button class="notif-bell" onclick="document.getElementById('notifDropdown').classList.toggle('show')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    
                    @if($notifications->isNotEmpty())
                        <span class="notif-dot"></span>
                    @endif
                </button>

                <div id="notifDropdown" class="notif-dropdown">
                    <div class="notif-dropdown-header">
                        <h3>Notifications</h3>
                        @if($notifications->isNotEmpty())
                            <a href="{{ route('employee.dashboard', ['clear_notifs' => 1]) }}">Mark all read</a>
                        @endif
                    </div>
                    
                    @if($notifications->isNotEmpty())
                        <ul class="notif-list">
                            @foreach($notifications as $n)
                                <li class="notif-item">
                                    {{ $n->message }}
                                    <span class="notif-time">{{ \Carbon\Carbon::parse($n->created_at)->format('M j, g:i A') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="notif-empty">You're all caught up!</div>
                    @endif
                </div>
            </div>

            <!-- User Menu -->
            <div class="user-menu-wrapper">
                <button class="user-menu-btn" onclick="document.getElementById('userDropdown').classList.toggle('show')">
                    <span class="user-name">{{ auth()->user()->username }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px;"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div id="userDropdown" class="user-dropdown">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Log out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-container">

        <div class="header" style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); padding: 32px; border-radius: 16px; margin-bottom: 32px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
            <div style="flex: 1; min-width: 250px; padding-right: 24px;">
                <h2 class="header-title" style="margin-top: 0; font-size: 28px; color: var(--text-primary);">My Tickets</h2>
                <p class="auth-subtitle" style="margin-bottom: 24px; color: var(--text-secondary); font-size: 16px;">Track and manage the status of your IT requests here.</p>
                <a href="{{ route('employee.submit') }}" class="button-primary" style="text-decoration:none;">+ New Ticket</a>
            </div>
            
            <div style="flex-basis: 200px; display: flex; justify-content: flex-end;">
                <img src="{{ asset('assets/images/maintenance.svg') }}" alt="IT Support" style="width: 100%; max-width: 200px; height: auto;">
            </div>
        </div>

        @if(session('flash_success'))
            <div class="alert-success">{{ session('flash_success') }}</div>
        @endif

        @php
            $pendingTickets = $complaints->where('status', 'pending');
            $inProgressTickets = $complaints->where('status', 'in_progress');
            $resolvedTickets = $complaints->whereNotIn('status', ['pending', 'in_progress']); 
        @endphp

        <div class="section-title" style="margin-top: 32px; margin-bottom: 16px;">
            <h3 style="color: var(--text-primary); font-size: 18px;">In Progress ({{ $inProgressTickets->count() }})</h3>
            <hr style="border: none; border-top: 1px solid #e5e7eb; margin-top: 8px;">
        </div>
        <div class="card-grid">
            @forelse ($inProgressTickets as $c)
                <div class="card-base">
                    <div style="display:flex; justify-content:space-between; margin-bottom: 16px;">
                        <span class="chip chip-primary">{{ $c->category }}</span>
                        <span class="chip chip-primary" style="background:#E0E7FF;">In Progress</span>
                    </div>
                    
                    <p style="font-size:15px; margin-bottom: 24px; color: var(--text-primary); flex-grow: 1; font-weight:bold;">
                        <strong>{!! nl2br(e($c->description)) !!}</strong>
                        <br><span style="font-weight: normal; font-size: 14px; color: var(--text-secondary);">Location: {{ $c->floor }}</span>
                    </p>
                    
                    @if ($c->attachment)
                        <a href="{{ asset('storage/' . $c->attachment) }}" target="_blank"
                            style="font-size:14px; font-weight:500; color:var(--primary); text-decoration:none; display:block; margin-bottom:16px;">📎
                            View Attachment</a>
                    @endif
                    
                    <div style="font-size:13px; color:var(--secondary);">
                        Submitted: {{ \Carbon\Carbon::parse($c->created_at)->format('M j, Y') }}
                    </div>
                </div>
            @empty
                <!-- EMPTY STATE DENGAN ILUSTRASI -->
                <div class="empty-state" style="padding: 40px 24px; text-align: center; background: #f9fafb; border-radius: 12px; border: 1px dashed #d1d5db; grid-column: 1 / -1;">
                    <img src="{{ asset('assets/images/maintenance.svg') }}" alt="No tickets" style="width: 120px; margin-bottom: 16px; opacity: 0.5; filter: grayscale(50%);">
                    <p style="margin:0; font-size: 16px; font-weight: 500; color: var(--text-primary);">No tasks in progress.</p>
                </div>
            @endforelse
        </div>

        <!-- 2. PENDING SECTION -->
        <div class="section-title" style="margin-top: 40px; margin-bottom: 16px;">
            <h3 style="color: var(--text-primary); font-size: 18px;">Pending ({{ $pendingTickets->count() }})</h3>
            <hr style="border: none; border-top: 1px solid #e5e7eb; margin-top: 8px;">
        </div>
        <div class="card-grid">
            @forelse ($pendingTickets as $c)
                <div class="card-base">
                    <div style="display:flex; justify-content:space-between; margin-bottom: 16px;">
                        <span class="chip chip-primary">{{ $c->category }}</span>
                        <span class="chip chip-warning">Pending</span>
                    </div>
                    
                    <p style="font-size:15px; margin-bottom: 24px; color: var(--text-primary); flex-grow: 1; font-weight:bold;">
                        <strong>{!! nl2br(e($c->description)) !!}</strong>
                        <br><span style="font-weight: normal; font-size: 14px; color: var(--text-secondary);">Location: {{ $c->floor }}</span>
                    </p>
                    
                    @if ($c->attachment)
                        <a href="{{ asset('storage/' . $c->attachment) }}" target="_blank"
                            style="font-size:14px; font-weight:500; color:var(--primary); text-decoration:none; display:block; margin-bottom:16px;">📎
                            View Attachment</a>
                    @endif
                    
                    <div style="font-size:13px; color:var(--secondary);">
                        Submitted: {{ \Carbon\Carbon::parse($c->created_at)->format('M j, Y') }}
                    </div>
                </div>
            @empty
                <!-- EMPTY STATE DENGAN ILUSTRASI -->
                <div class="empty-state" style="padding: 40px 24px; text-align: center; background: #f9fafb; border-radius: 12px; border: 1px dashed #d1d5db; grid-column: 1 / -1;">
                    <img src="{{ asset('assets/images/maintenance.svg') }}" alt="No tickets" style="width: 120px; margin-bottom: 16px; opacity: 0.5; filter: grayscale(50%);">
                    <p style="margin:0; font-size: 16px; font-weight: 500; color: var(--text-primary);">You're all caught up!</p>
                    <p style="margin-top:4px; font-size: 14px; color: var(--text-secondary);">There are no pending tickets at the moment.</p>
                </div>
            @endforelse
        </div>

        <!-- 3. RESOLVED SECTION -->
        <div class="section-title" style="margin-top: 40px; margin-bottom: 16px;">
            <h3 style="color: var(--text-primary); font-size: 18px;">Resolved ({{ $resolvedTickets->count() }})</h3>
            <hr style="border: none; border-top: 1px solid #e5e7eb; margin-top: 8px;">
        </div>
        <div class="card-grid">
            @forelse ($resolvedTickets as $c)
                <div class="card-base" style="opacity: 0.85;">
                    <div style="display:flex; justify-content:space-between; margin-bottom: 16px;">
                        <span class="chip chip-primary">{{ $c->category }}</span>
                        <span class="chip chip-success">Resolved</span>
                    </div>
                    
                    <p style="font-size:15px; margin-bottom: 24px; color: var(--text-primary); flex-grow: 1; font-weight:bold;">
                        <strong>{!! nl2br(e($c->description)) !!}</strong>
                        <br><span style="font-weight: normal; font-size: 14px; color: var(--text-secondary);">Location: {{ $c->floor }}</span>
                    </p>
                    
                    @if ($c->attachment)
                        <a href="{{ asset('storage/' . $c->attachment) }}" target="_blank"
                            style="font-size:14px; font-weight:500; color:var(--primary); text-decoration:none; display:block; margin-bottom:16px;">📎
                            View Attachment</a>
                    @endif
                    
                    <div style="font-size:13px; color:var(--secondary);">
                        Submitted: {{ \Carbon\Carbon::parse($c->created_at)->format('M j, Y') }}
                    </div>
                </div>
            @empty
                <!-- EMPTY STATE DENGAN ILUSTRASI -->
                <div class="empty-state" style="padding: 40px 24px; text-align: center; background: #f9fafb; border-radius: 12px; border: 1px dashed #d1d5db; grid-column: 1 / -1;">
                    <img src="{{ asset('assets/images/maintenance.svg') }}" alt="No tickets" style="width: 120px; margin-bottom: 16px; opacity: 0.5; filter: grayscale(50%);">
                    <p style="margin:0; font-size: 16px; font-weight: 500; color: var(--text-primary);">No resolved tickets yet.</p>
                </div>
            @endforelse
        </div>

    </div>
</body>

</html>