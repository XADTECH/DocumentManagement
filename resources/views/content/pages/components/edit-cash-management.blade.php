<style>
    /* Modal Height */
    .modal-dialog {
        max-height: 550px;
        /* Set maximum height for the modal */
        height: 550px;
        display: flex;
        align-items: center;
    }

    .modal-content {
        height: 100%;
    }

    /* Custom Scrollbar Styles */
    .modal-body {
        /* Custom scrollbar for Webkit browsers (Chrome, Safari) */
        overflow-x: auto;
        scrollbar-width: thin;
        /* For Firefox */
        scrollbar-color: #0067aa #e0e0e0;
        /* For Firefox */
    }

    .modal-body::-webkit-scrollbar {
        width: 8px;
        /* Width of the scrollbar */
    }

    .modal-body::-webkit-scrollbar-thumb {
        background-color: #0067aa;
        /* Color of the scrollbar thumb */
        border-radius: 4px;
        /* Optional: Rounded corners for the scrollbar thumb */
    }

    .modal-body::-webkit-scrollbar-track {
        background-color: #e0e0e0;
        /* Color of the scrollbar track */
    }
</style>
<div class="container mt-4">
    <div class="card mt-4">
        <div class="card-body">
            <div class="dropdown-section">
                <h3 class="dropdown-header">Cash Management ▼</h3>
                <div class="dropdown-content">
                    <!-- Salary Section -->
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Cash Requirement For CAPEX : {{ number_format($totalCapitalExpenditure) }} AED</h5>
                                @php
                                    $totalOPEX = $totalDirectCost + $totalInDirectCost + $totalNetProfitBeforeTax;
                                @endphp
                                <h5>Cash Requirement For OPEX: {{ number_format($totalOPEX) }} AED</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>



<script></script>
