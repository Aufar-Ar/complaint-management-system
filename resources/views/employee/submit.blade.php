<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Ticket - IT CMS</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
    <!-- Genesis Sticky Nav -->
    <div class="top-nav">
        <h1>IT Workspace</h1>
        <div>
            <a href="{{ route('employee.dashboard') }}" class="button-ghost" style="margin-right:8px;">Back to Dashboard</a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="button-ghost">Log out</button>
            </form>
        </div>
    </div>

    <div class="dashboard-container" style="max-width: 600px; margin: 64px auto;">
        
        <div class="card-base" style="padding: 40px;">
            <h2 class="auth-title">Submit IT Problem</h2>
            <p class="auth-subtitle">Provide details so our technicians can help you quickly.</p>

        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <form action="{{ route('employee.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" class="text-input" style="appearance: auto;" required>
                    <option value="" disabled selected>Select a category...</option>
                    <option value="Hardware" {{ old('category') == 'Hardware' ? 'selected' : '' }}>Hardware (e.g., broken screen, mouse not working)</option>
                    <option value="Software" {{ old('category') == 'Software' ? 'selected' : '' }}>Software (e.g., app crashes, license expired)</option>
                    <option value="Network"  {{ old('category') == 'Network'  ? 'selected' : '' }}>Network (e.g., no internet, VPN issues)</option>
                    <option value="Other"    {{ old('category') == 'Other'    ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="floor">Floor / Location</label>
                <input type="text" id="floor" name="floor" class="text-input" placeholder="e.g. 5th Floor, Room 502" value="{{ old('floor') }}" required>
            </div>
            
            <div class="form-group">
                <label for="description">Problem Description</label>
                <textarea id="description" name="description" class="text-input" style="height: 140px; resize: vertical;" placeholder="Please describe exactly what is happening..." required>{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label for="attachment">Attachment (Optional - Screenshot)</label>
                <input type="file" id="attachment" name="attachment" style="font-size:14px; margin-top:8px;" accept=".jpg,.jpeg,.png,.pdf">
                <small style="color:var(--slate); margin-top:6px; display:block;">Max size 2MB. Allowed types: JPG, PNG, PDF.</small>
            </div>

            <button type="submit" class="button-primary" style="margin-top: 16px;">Submit Ticket</button>
        </form>
        </div>
    </div>
</body>
</html>
