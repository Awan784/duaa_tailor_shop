@extends('admin.layout.interface')
@section('content')
    <!-- TABLE START -->
    <div class="main_content_iner overly_inner">
        <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div
                    class="page_title_box d-flex align-items-center justify-content-between">
                    <div class="page_title_left">
                    <h3 class="f_s_30 f_w_700 text_white">
                        Sales
                    </h3>
                    <ol class="breadcrumb page_bradcam mb-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">Darzi Shop </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{url('dashboard')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Sales
                        </li>
                    </ol>
                    </div>
                    <!-- <a href="#" class="white_btn3">Add Company</a> -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
              <div class="white_card">
                <div class="card-body">
                  <div class="container-fluid p-0">
                    <div class="row justify-content-center">
                      <div class="col-lg-12">
                        <div class="white_card card_height_100 mb_30">
                          <div class="white_card_header">
                            <div class="m-0">
                              <div class="main-title">
                                <!-- <h3 class="m-0">Data table</h3> -->
                              </div>
                            </div>
                          </div>
                          <div class="white_card_body">
                            <div class="QA_section">
                              <div class="white_box_tittle list_header">
                                <h4></h4>
                                <div class="box_right d-flex lms_block">
                                  <div class="serach_field_2 me-2">
                                    <div class="search_inner">
                                      <form action="{{ route('sales.index') }}" method="GET">
                                        <div class="search_field">
                                          <input
                                            type="text"
                                            name="sku"
                                            value="{{ $sku ?? request('sku') }}"
                                            placeholder="Search by SKU (e.g. DC-12)"
                                          />
                                        </div>
                                        <button type="submit">
                                          <i class="ti-search"></i>
                                        </button>
                                      </form>
                                    </div>
                                  </div>
                                  <div class="serach_field_2">
                                    <div class="search_inner">
                                      <form action="#">
                                        <div class="search_field">
                                          <input type="text" placeholder="Search content here..." id="customSearchDataTable"/>
                                        </div>
                                        <button type="submit">
                                          <i class="ti-search"></i>
                                        </button>
                                      </form>
                                    </div>
                                  </div>
                                  @if(($sku ?? request('sku')) )
                                    <div class="add_button ms-2">
                                      <a href="{{ route('sales.index') }}" class="btn_1">Clear</a>
                                    </div>
                                  @endif
                                  <div class="add_button ms-2">
                                    <a
                                      href="{{route('sales.create')}}"
                                      class="btn_1"
                                      >Add New</a
                                    >
                                  </div>
                                </div>
                              </div>
                              <div class="QA_table mb_30">
                                <table class="table lms_table_active">
                                  <thead>
                                    <tr>
                                      <th scope="col">Sr#</th>
                                      <th scope="col">Bill No</th>
                                      <th scope="col">Customer</th>
                                      <th scope="col">Date</th>
                                      <th scope="col">Status</th>
                                      <th scope="col">Net Total</th>
                                      <th scope="col">Created By</th>
                                      <th scope="col">Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach ($totalData as $key => $data)
                                      <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$data->bill_no}}</td>
                                        <td>{{$data->customer ? $data->customer->name : '-'}}</td>
                                        <td>{{$data->date ? date('Y-m-d', strtotime($data->date)) : '-'}}</td>
                                        <td>
                                          @auth
                                            @if(auth()->user()->user_type == 1)
                                              <select name="status" class="form-control form-control-sm statusSelect" data-sale-id="{{$data->id}}" data-current-status="{{$data->status}}" style="width: auto; display: inline-block;">
                                                <option value="Inprocessing" {{$data->status == 'Inprocessing' ? 'selected' : ''}}>Inprocessing</option>
                                                <option value="Completed" {{$data->status == 'Completed' ? 'selected' : ''}}>Completed</option>
                                              </select>
                                            @else
                                              <span class="badge {{$data->status == 'Completed' ? 'bg-success' : 'bg-warning'}}">
                                                {{$data->status}}
                                              </span>
                                            @endif
                                          @else
                                            <span class="badge {{$data->status == 'Completed' ? 'bg-success' : 'bg-warning'}}">
                                              {{$data->status}}
                                            </span>
                                          @endauth
                                        </td>
                                        <td>{{number_format($data->net_total, 2)}}</td>
                                        <td>
                                            @if(isset($data->createdByUser))
                                                {{$data->createdByUser->name}}
                                            @elseif($data->createdBy)
                                                {{$data->createdBy->name}}
                                            @elseif($data->created_by)
                                                User ID: {{$data->created_by}}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="td-width">
                                          <a href="{{ route('sales.print',$data->id)}}" class="btn btn-primary btn-sm" target="_blank">
                                            <i class="fa fa-print"></i>
                                          </a>
                                          <a href="{{ route('sales.edit',$data->id)}}" class="btn btn-dark btn-sm">
                                            <i class="fa fa-edit"></i>
                                          </a>
                                          <a href="{{ route('sales.uploadImages',$data->id)}}" class="btn btn-info btn-sm">
                                            <i class="fa fa-image"></i>
                                          </a>
                                          @auth
                                            @if(auth()->user()->user_type == 1 && $data->status == 'Inprocessing')
                                              <a type="button" class="btn btn-danger btn-sm modalDeleteButton" data-form-action="{{url('sales/'.$data->id)}}" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal1">
                                                <i class="fa fa-trash"></i>
                                              </a>
                                            @endif
                                          @endauth
                                        </td>
                                      </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-12"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <!-- TABLE END -->
    
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal1" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this sale? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal for Completed Status -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Add Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="paymentForm" action="{{ route('sale.status.change') }}" method="POST">
                    @csrf
                    <input type="hidden" name="saleId" id="paymentSaleId">
                    <input type="hidden" name="status" value="Completed">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="cash_received" class="form-label">Cash Received</label>
                            <input type="number" class="form-control" id="cash_received" name="cash_received" value="0" step="0.01" min="0">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Complete Sale</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete button handler
            const deleteButtons = document.querySelectorAll('.modalDeleteButton');
            const deleteForm = document.getElementById('deleteForm');
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const formAction = this.getAttribute('data-form-action');
                    deleteForm.action = formAction;
                });
            });

            // Status change handler
            const statusSelects = document.querySelectorAll('.statusSelect');
            statusSelects.forEach(select => {
                select.addEventListener('change', function() {
                    const saleId = this.getAttribute('data-sale-id');
                    const currentStatus = this.getAttribute('data-current-status');
                    const newStatus = this.value;

                    // If changing to Completed, show payment modal
                    if (newStatus === 'Completed' && currentStatus !== 'Completed') {
                        document.getElementById('paymentSaleId').value = saleId;
                        const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
                        paymentModal.show();
                        
                        // Reset dropdown if modal is closed without submitting
                        paymentModal._element.addEventListener('hidden.bs.modal', function() {
                            select.value = currentStatus;
                        }, { once: true });
                    } else if (newStatus === 'Inprocessing') {
                        // Directly submit for Inprocessing
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route("sale.status.change") }}';
                        form.innerHTML = `
                            @csrf
                            <input type="hidden" name="saleId" value="${saleId}">
                            <input type="hidden" name="status" value="Inprocessing">
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection

