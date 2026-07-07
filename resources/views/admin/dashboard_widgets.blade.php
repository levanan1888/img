<div class="row">
    <!-- Card 1: Total Conversions -->
    <div class="col-md-3">
        <div class="card text-white bg-dark mb-3">
            <div class="card-header uppercase tracking-wider font-semibold text-xs">Total Requests</div>
            <div class="card-body">
                <h5 class="card-title text-2xl font-bold">{{ number_format($totalConversions) }}</h5>
                <p class="card-text text-zinc-400 text-xs">conversions processed</p>
            </div>
        </div>
    </div>
    <!-- Card 2: Success Rate -->
    <div class="col-md-3">
        <div class="card text-white bg-dark mb-3">
            <div class="card-header uppercase tracking-wider font-semibold text-xs">Success Rate</div>
            <div class="card-body">
                <h5 class="card-title text-2xl font-bold text-success">{{ $successRate }}%</h5>
                <p class="card-text text-zinc-400 text-xs">completed successfully</p>
            </div>
        </div>
    </div>
    <!-- Card 3: Bandwidth -->
    <div class="col-md-3">
        <div class="card text-white bg-dark mb-3">
            <div class="card-header uppercase tracking-wider font-semibold text-xs">Bandwidth Processed</div>
            <div class="card-body">
                <h5 class="card-title text-2xl font-bold">{{ $totalDataProcessed }}</h5>
                <p class="card-text text-zinc-400 text-xs">in total size</p>
            </div>
        </div>
    </div>
    <!-- Card 4: Avg Time -->
    <div class="col-md-3">
        <div class="card text-white bg-dark mb-3">
            <div class="card-header uppercase tracking-wider font-semibold text-xs">Avg Duration</div>
            <div class="card-body">
                <h5 class="card-title text-2xl font-bold">{{ $avgExecutionTime }}s</h5>
                <p class="card-text text-zinc-400 text-xs">per image conversion</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Purge Temp Cache Card -->
    <div class="col-md-8">
        <div class="card bg-light mb-3">
            <div class="card-header font-bold uppercase text-xs">Temporary Workspace Files</div>
            <div class="card-body">
                <p class="card-text text-sm">Images processed on the server are stored in isolated workspace nodes. They should automatically be purged by finalization scripts. However, you can force-purge all remaining residues here.</p>
                <hr>
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="text-sm font-semibold text-muted">
                        Files count: <span class="text-dark">{{ $tempFilesCount }}</span> | Total Size: <span class="text-dark">{{ $formattedTempSize }}</span>
                    </div>
                    <form action="{{ admin_url('purge-temp') }}" method="POST" onsubmit="return confirm('Purge all temporary workspace files?');">
                        @csrf
                        <button type="submit" class="btn btn-danger font-bold text-xs uppercase tracking-wider">
                            Purge Workspace Cache
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- System info -->
    <div class="col-md-4">
        <div class="card bg-light mb-3">
            <div class="card-header font-bold uppercase text-xs">System Parameters</div>
            <div class="card-body py-3">
                <div class="d-flex justify-content-between text-xs border-bottom pb-2 mb-2">
                    <span class="text-muted">Max Upload Size</span>
                    <span class="font-bold">20 MB</span>
                </div>
                <div class="d-flex justify-content-between text-xs border-bottom pb-2 mb-2">
                    <span class="text-muted">Input formats</span>
                    <span class="font-bold">JPG, WEBP, BMP, PNG</span>
                </div>
                <div class="d-flex justify-content-between text-xs">
                    <span class="text-muted">Target formats</span>
                    <span class="font-bold">PNG, JPG, WEBP, BMP</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card bg-light mb-3">
            <div class="card-header font-bold uppercase text-xs">Traffic & Service Usage (Last 7 Days)</div>
            <div class="card-body" style="height: 350px; position: relative;">
                <canvas id="usageChart" style="width: 100%; height: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('usageChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($chartData, 'date')) !!},
                datasets: [
                    {
                        label: 'Total Requests (Visits)',
                        data: {!! json_encode(array_column($chartData, 'total')) !!},
                        borderColor: '#6c757d',
                        backgroundColor: 'rgba(108, 117, 125, 0.05)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Successful Conversions (Service Usage)',
                        data: {!! json_encode(array_column($chartData, 'success')) !!},
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.05)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
