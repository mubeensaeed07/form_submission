@extends('layouts.app')

@section('title', 'Agents')

@section('content')
<div class="card card-soft">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Agents</h5>
            <a href="{{ route('admin.agents.create') }}" class="btn btn-primary btn-sm">Add Agent</a>
        </div>

        @if(session('status'))
            <div class="alert alert-success py-2">{{ session('status') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agents as $agent)
                        <tr>
                            <td>{{ $agent->name }}</td>
                            <td>{{ $agent->email }}</td>
                            <td>
                                @php
                                    $badgeClass = [
                                        'invited' => 'secondary',
                                        'pending' => 'warning',
                                        'active' => 'success',
                                        'disabled' => 'dark',
                                        'declined' => 'danger',
                                    ][$agent->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($agent->status) }}</span>
                            </td>
                            <td>{{ $agent->created_at?->format('Y-m-d H:i') }}</td>
                            <td class="text-end">
                                <div class="btn-group">
                                    @if(in_array($agent->status, ['invited', 'pending', 'declined']))
                                        <form method="POST" action="{{ route('admin.agents.updateStatus', $agent) }}" class="me-1">
                                            @csrf
                                            <input type="hidden" name="action" value="approve">
                                            <button class="btn btn-success btn-sm">Approve</button>
                                        </form>
                                    @endif

                                    @if($agent->status === 'pending')
                                        <form method="POST" action="{{ route('admin.agents.updateStatus', $agent) }}" class="me-1">
                                            @csrf
                                            <input type="hidden" name="action" value="decline">
                                            <button class="btn btn-outline-danger btn-sm">Decline</button>
                                        </form>
                                    @endif

                                    @if($agent->status === 'active')
                                        <form method="POST" action="{{ route('admin.agents.updateStatus', $agent) }}" class="me-1">
                                            @csrf
                                            <input type="hidden" name="action" value="disable">
                                            <button class="btn btn-outline-warning btn-sm">Disable</button>
                                        </form>
                                    @elseif($agent->status === 'disabled')
                                        <form method="POST" action="{{ route('admin.agents.updateStatus', $agent) }}" class="me-1">
                                            @csrf
                                            <input type="hidden" name="action" value="enable">
                                            <button class="btn btn-outline-success btn-sm">Enable</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No agents created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $agents->links() }}
        </div>
    </div>
</div>
@endsection


