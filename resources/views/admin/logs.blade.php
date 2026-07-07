@extends('admin.layout')

@section('title', 'Conversion Logs')
@section('page-title', 'Conversion History')

@section('content')

    <!-- Logs list card -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
        <div>
            <div class="p-6 border-b border-zinc-800">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Conversion Transaction Logs</h3>
                <p class="text-[11px] text-zinc-400">Auditable list of image conversion events processed on your servers.</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-xs text-zinc-300">
                    <thead class="bg-zinc-950 text-zinc-400 uppercase text-[10px] font-bold tracking-wider border-b border-zinc-800">
                        <tr>
                            <th class="py-4 px-6">File Name</th>
                            <th class="py-4 px-6">Original Size</th>
                            <th class="py-4 px-6">Route</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6">Duration</th>
                            <th class="py-4 px-6">IP Address</th>
                            <th class="py-4 px-6">Timestamp</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/60">
                        @forelse($logs as $log)
                            <tr class="hover:bg-zinc-800/25 transition-colors">
                                <td class="py-3.5 px-6 font-medium text-white max-w-[180px] truncate" title="{{ $log->file_name }}">
                                    {{ $log->file_name }}
                                </td>
                                <td class="py-3.5 px-6 font-mono text-zinc-400">
                                    @if($log->file_size <= 0)
                                        0 Bytes
                                    @elseif($log->file_size < 1024)
                                        {{ $log->file_size }} Bytes
                                    @elseif($log->file_size < 1048576)
                                        {{ round($log->file_size / 1024, 2) }} KB
                                    @else
                                        {{ round($log->file_size / 1048576, 2) }} MB
                                    @endif
                                </td>
                                <td class="py-3.5 px-6">
                                    <span class="inline-flex items-center gap-1 font-mono text-[10px] font-bold uppercase">
                                        <span class="text-zinc-500">{{ $log->source_format }}</span>
                                        <svg class="w-2.5 h-2.5 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                        </svg>
                                        <span class="text-emerald-500">{{ $log->target_format }}</span>
                                    </span>
                                </td>
                                <td class="py-3.5 px-6">
                                    @if($log->status === 'success')
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-400 uppercase tracking-wider bg-emerald-950/30 border border-emerald-900/60 px-2 py-0.5 rounded-md">
                                            Success
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-red-400 uppercase tracking-wider bg-red-950/30 border border-red-900/60 px-2 py-0.5 rounded-md" title="{{ $log->error_message }}">
                                            Failed
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-6 font-mono text-zinc-400">
                                    {{ number_format($log->execution_time, 3) }}s
                                </td>
                                <td class="py-3.5 px-6 font-mono text-zinc-500">
                                    {{ $log->ip_address }}
                                </td>
                                <td class="py-3.5 px-6 text-zinc-500">
                                    {{ $log->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="py-3.5 px-6 text-right">
                                    <form action="{{ route('admin.logs.delete', $log->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this log entry?');">
                                        @csrf
                                        <button type="submit" class="text-zinc-500 hover:text-red-400 transition-colors focus:outline-none">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-zinc-500 font-medium">No conversion logs available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($logs->hasPages())
            <div class="p-6 border-t border-zinc-800 bg-zinc-950/20">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

@endsection
