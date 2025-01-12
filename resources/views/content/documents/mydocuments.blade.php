@extends('layouts/contentNavbarLayout')

@section('title', 'My Documents')

@section('content')
    <style>
        .table-container {
            margin-top: 20px;
        }

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .filter-container {
            margin-bottom: 20px;
            text-align: right;
        }

        .filter-input {
            width: 300px;
            display: inline-block;
        }
    </style>

    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Document Management /</span> My Documents
    </h4>

    <div class="card mb-4">
        <h5 class="card-header">Search</h5>
        <div class="card-body">
            <div class="filter-container">
                <input type="text" id="filterGlobal" class="form-control filter-input" placeholder="Search...">
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <h5 class="card-header">My Documents</h5>
        <div class="table-responsive table-container">
            <table class="table table-hover" id="documentsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Document</th>
                        <th>Department</th>
                        <th>Sub Department</th>
                        <th>Type</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($documents as $index => $document)
                        @php
                            $filePaths = json_decode($document->file_paths, true);
                        @endphp

                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="searchable">{{ $document->name }}</td>
                            <td class="searchable">{{ $document->department->name ?? 'N/A' }}</td>
                            <td class="searchable">{{ $document->subcategory->name ?? 'N/A' }}</td>
                            <td class="searchable">{{ $document->documentType->name ?? 'N/A' }}</td>
                            <td class="searchable">
                                {{ $document->user->first_name . ' ' . $document->user->last_name ?? 'N/A' }}
                            </td>
                            <td>
                                <button
                                    class="badge 
                                    {{ $document->approval_status === 'Approved' ? 'bg-success' : '' }}
                                    {{ $document->approval_status === 'Pending' ? 'bg-warning' : '' }}
                                    {{ $document->approval_status === 'Rejected' ? 'bg-danger' : '' }}"
                                    onclick="openStatusModal({{ $document->id }}, '{{ $document->approval_status }}')"
                                    @if (!in_array(auth()->user()->role, ['Admin', 'CEO', 'Secretary'])) disabled 
                                    style="border: 2px solid white" @endif>
                                    {{ $document->approval_status }}
                                </button>
                            </td>
                            <td>
                                <a href="javascript:void(0)" class="btn btn-sm btn-primary"
                                    data-document-id="{{ $document->id }}" data-file-paths="{{ $document->file_paths }}"
                                    data-remarks="{{ $document->remarks }}" onclick="showDocumentFiles(this)">
                                    Details
                                </a>

                                @if (auth()->user()->hasRole(['Admin', 'CEO', 'Secretary']))
                                    <a href="{{ route('documents.edit', $document->id) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('documents.destroy', $document->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this document?');">
                                            Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No documents found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- View Document Modal -->
    <div class="modal fade" id="viewDocumentModal" tabindex="-1" aria-labelledby="viewDocumentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDocumentModalLabel">Document Files</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="documentFiles" class="">
                        <!-- Files will be dynamically added here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Change Approval Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="statusForm" action="{{ route('documents.updateStatus') }}" method="POST">
                        @csrf
                        <input type="hidden" id="documentId" name="document_id">
                        <div class="mb-3">
                            <label for="newStatus" class="form-label">Select Status</label>
                            <select class="form-select" id="newStatus" name="status" required>
                                <option value="Approved">Approved</option>
                                <option value="Pending">Pending</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        function showDocumentFiles(button) {
            console.log(button);
            const filePaths = JSON.parse(button.getAttribute('data-file-paths'));
            
            const modalBody = document.getElementById('documentFiles');
            const remarks = button.getAttribute('data-remarks'); // Get remarks from data attribute
            modalBody.innerHTML = ''; // Clear any existing content

            if (Array.isArray(filePaths) && filePaths.length > 0) {
                filePaths.forEach(filePath => {
                    const fileExtension = filePath.split('.').pop().toLowerCase();
                    const fileName = filePath.split('/').pop(); // Extract the file name from the path
                    let fileElement;


                    if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                        fileElement = `
                        <div class="file-item">
                            <a href="${filePath}" target="_blank">
                                <img src="${filePath}" alt="${fileName}" style="width: 100px; height: auto; border: 1px solid #ddd;">
                            </a>
                            <p class="file-name">${fileName}</p>
                        </div>
                    `;
                    } else if (fileExtension === 'pdf') {
                        fileElement = `
                        <div class="file-item">
                            <a href="${filePath}" target="_blank">
                                <img src="https://cdn.pixabay.com/photo/2017/03/08/21/19/file-2127825_1280.png" alt="PDF File" style="width: 100px; height: auto;">
                            </a>
                            <p class="file-name">${fileName}</p>
                        </div>
                    `;
                    } else if (fileExtension === 'docx' || fileExtension === 'doc') {
                        fileElement = `
                        <div class="file-item">
                            <a href="${filePath}" target="_blank">
                                <img src="https://cdn.pixabay.com/photo/2017/03/08/21/19/file-2127825_1280.png" alt="Word File" style="width: 100px; height: auto;">
                            </a>
                            <p class="file-name">${fileName}</p>
                        </div>
                    `;
                    } else {
                        fileElement = `
                        <div class="file-item">
                            <a href="${filePath}" target="_blank" class="btn btn-primary">
                                View File
                            </a>
                            <p class="file-name">${fileName}</p>
                        </div>
                    `;
                    }

                    modalBody.innerHTML += fileElement;
                });
                if (remarks) {
                    modalBody.innerHTML +=
                        `<p><span style="color: red; font-weight: bold;">Remarks:</span> ${remarks}</p>`;
                } else {
                    modalBody.innerHTML +=
                        `<p><span style="color: red; font-weight: bold;">Remarks:</span> No remarks available.</p>`;
                }
            } else {
                modalBody.innerHTML = '<p>No files available for this document.</p>';
            }

            const modal = new bootstrap.Modal(document.getElementById('viewDocumentModal'));
            modal.show();
        }


        function openStatusModal(documentId, currentStatus) {
            document.getElementById('documentId').value = documentId;
            document.getElementById('newStatus').value = currentStatus;
            document.getElementById('remarks').value = '';

            new bootstrap.Modal(document.getElementById('statusModal')).show();
        }

        const filterInput = document.getElementById('filterGlobal');
        const tableRows = document.querySelectorAll('#documentsTable tbody tr');

        filterInput.addEventListener('input', function() {
            const filterText = filterInput.value.toLowerCase();

            tableRows.forEach(row => {
                const rowText = Array.from(row.querySelectorAll('.searchable')).map(cell =>
                    cell.textContent.toLowerCase()
                ).join(' ');

                row.style.display = rowText.includes(filterText) ? '' : 'none';
            });
        });
    </script>
@endsection
