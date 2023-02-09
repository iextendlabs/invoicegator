@extends('customLayout.layout')

@section('title', 'Invoice')

<style>
body{
    margin-top:20px;
    color: #484b51;
}
.text-secondary-d1 {
    color: #728299!important;
}
.page-header {
    margin: 0 0 1rem;
    padding-bottom: 1rem;
    padding-top: .5rem;
    border-bottom: 1px dotted #e2e2e2;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-pack: justify;
    justify-content: space-between;
    -ms-flex-align: center;
    align-items: center;
}
.page-title {
    padding: 0;
    margin: 0;
    font-size: 1.75rem;
    font-weight: 300;
}

@page {
    size: A4 !important;
    margin: 0 !important;
}
@media print {
    .page {
        margin: 0;
        border: initial;
        border-radius: initial;
        width: initial;
        min-height: initial;
        box-shadow: initial;
        background: initial;
        page-break-after: always;
    }
}
.brc-default-l1 {
    border-color: #dce9f0!important;
}

.ml-n1, .mx-n1 {
    margin-left: -.25rem!important;
}
.mr-n1, .mx-n1 {
    margin-right: -.25rem!important;
}
.mb-4, .my-4 {
    margin-bottom: 1.5rem!important;
}

hr {
    margin-top: 1rem;
    margin-bottom: 1rem;
    border: 0;
    border-top: 1px solid rgba(0,0,0,.1);
}

.text-grey-m2 {
    color: #888a8d!important;
}

.text-success-m2 {
    color: #86bd68!important;
}

.font-bolder, .text-600 {
    font-weight: 600!important;
}

.text-110 {
    font-size: 110%!important;
}
.text-blue {
    color: #478fcc!important;
}
.pb-25, .py-25 {
    padding-bottom: .75rem!important;
}

.pt-25, .py-25 {
    padding-top: .75rem!important;
}
.bgc-default-tp1 {
    background-color: rgba(121,169,197,.92)!important;
}
.bgc-default-l4, .bgc-h-default-l4:hover {
    background-color: #f3f8fa!important;
}
.page-header .page-tools {
    -ms-flex-item-align: end;
    align-self: flex-end;
}

.btn-light {
    color: #757984;
    background-color: #f5f6f9;
    border-color: #dddfe4;
}
.w-2 {
    width: 1rem;
}

.text-120 {
    font-size: 120%!important;
}
.text-primary-m1 {
    color: #4087d4!important;
}

.text-danger-m1 {
    color: #dd4949!important;
}
.text-blue-m2 {
    color: #68a3d5!important;
}
.text-150 {
    font-size: 150%!important;
}
.text-60 {
    font-size: 60%!important;
}
.text-grey-m1 {
    color: #7b7d81!important;
}
.align-bottom {
    vertical-align: bottom!important;
}
</style>
<!-- navbar -->

@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    {{-- <a href="{{ route('view.project', ['id' => $projectID]) }}" class="nav-link text-light"><i class="bi bi-arrow-left-circle"></i></a> --}}
    <a class="navbar-brand" href="#">Admin | <small>Invoice Details</small></a>
    <a href="{{ route('adminlogout') }}" class="nav-link ml-auto text-light">Logout</a>
    <!-- <button class="btn btn-danger" onclick="window.print()"><i class="bi bi-printer"></i> Print Invoice</button> -->
    <button class="btn btn-danger" id="button"><i class="bi bi-printer"></i> Print Invoice</button>
</nav>
@endsection

@section('main-content')
<div class="page-content container">
    <div class="container px-0">
        <div class="row mt-4">
            <div class="col-12 col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="text-center text-150">
                            <i class="fa fa-book fa-2x text-success-m2 mr-1"></i>
                            <span class="text-default-d3"><h1>Saqib Ashraf</h1></span>
                        </div>
                    </div>
                </div>
                <!-- .row -->

                <hr class="row brc-default-l1 mx-n1 mb-4" />

                <div class="row">
                    <div class="col-sm-6">
                        <div>
                            <span class="text-sm text-grey-m2 align-middle">To:</span>
                            <span class="text-600 text-110 text-blue align-middle">Sulzer Consulting</span>
                        </div>
                        <div class="text-grey-m2">
                            <div class="my-1">
                                Postfach 7203
                            </div>
                            <div class="my-1">
                                CH-6302 Zug
                            </div>
                            <div class="my-1"><i class="fa fa-phone fa-flip-horizontal text-secondary"></i> <b class="text-600">Switzerland</b></div>
                        </div>
                    </div>
                    <!-- /.col -->

                    <div class="text-95 col-sm-6 align-self-start d-sm-flex justify-content-end">
                        <hr class="d-sm-none" />
                        <div class="text-grey-m2">
                            <div class="mt-1 mb-2 text-secondary-m1 text-600 text-125">
                                Invoice
                            </div>

                            <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">ID:</span> @if($invoice->invoice_title ) {{ $invoice->invoice_title }} @else Null @endif</div>

                            <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">Issue Date:</span> {{ $invoice->created_at->format('d-M-Y') }}</div>

                            <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">Customer ID:</span> <span class="badge badge-warning badge-pill px-25">Romeo Sulzer</span></div>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>

                <div class="container mt-4">
                    <div class="row text-600 text-white bgc-default-tp1 py-25">
                        <div class="d-none d-sm-block col-12">Quote/Project Description: {{ $invoice->project_name }}</div>
                    </div>
                </div>

                <div class="container mt-4"> 
                    <div class="row text-600 text-white bgc-default-tp1 py-25">
                        <div class="col-8">Description</div>
                        <div class="col-4 text-right">Line Total</div>
                    </div>

                    <div class="text-95 text-secondary-d3">
                        <div class="row mb-2 mb-sm-0 py-25">
                            <div class="d-none d-sm-block col-8">Payment {{ $invoice->total_hours }} hrs ($25/hr)</div>
                            <div class="col-4 text-right">${{ $invoice->invoice_rate }}</div>
                        </div>

                        <div class="row mb-2 mb-sm-0 py-25 bgc-default-l4">
                            <div class="d-none d-sm-block col-8"></div>
                            <div class="col-4 text-right"></div>
                        </div>

                        <div class="row mb-2 mb-sm-0 py-25">
                            <div class="d-none d-sm-block col-8"></div>
                            <div class="col-4 text-right"></div>
                        </div>

                        <div class="row mb-2 mb-sm-0 py-25 bgc-default-l4">
                            <div class="d-none d-sm-block col-8"></div>
                            <div class="col-4 text-right"></div>
                        </div>
                    </div>

                    <div class="row border-b-2 brc-default-l2"></div>                   
                    <!-- <div class="table-responsive">
                        <table class="table table-striped table-bordered border-b-2 brc-default-l1">
                            <thead class="bg-none bgc-default-tp1">
                                <tr class="text-white">
                                    <th>Description</th>
                                    <th class="text-right">Line Total</th>
                                </tr>
                            </thead>

                            <tbody class="text-95 text-secondary-d3">
                                <tr></tr>
                                <tr>
                                    <td>Payment {{ $invoice->total_hours }} hrs</td>
                                    <td class="text-secondary-d2 text-right">${{ $invoice->invoice_rate }}</td>
                                </tr>
                                <tr><td></td><td></td></tr>
                                <tr><td></td><td></td></tr>
                                <tr><td></td><td></td></tr>
                                <tr><td></td><td></td></tr> 
                            </tbody>
                        </table>
                    </div> -->

                    <div class="row mt-3">
                        <div class="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">
                             
                        </div>

                        <div class="col-12 col-sm-5 text-grey text-90 order-first order-sm-last text-right">
                            <div class="row my-2">
                                <div class="col-7 text-right">
                                    SubTotal
                                </div>
                                <div class="col-5">
                                    <span class="text-120 text-secondary-d1">${{ $invoice->invoice_rate }}</span>
                                </div>
                            </div>

                            <div class="row my-2">
                                <div class="col-7 text-right">
                                    Discount
                                </div>
                                <div class="col-5">
                                    <span class="text-110 text-secondary-d1">-</span>
                                </div>
                            </div>

                            <div class="row my-2">
                                <div class="col-7 text-right">
                                    Sales Tax Rate (%)
                                </div>
                                <div class="col-5">
                                    <span class="text-110 text-secondary-d1">0</span>
                                </div>
                            </div>

                            <div class="row my-2">
                                <div class="col-7 text-right">
                                    Sales Tax
                                </div>
                                <div class="col-5">
                                    <span class="text-110 text-secondary-d1">-</span>
                                </div>
                            </div>

                            <div class="row my-2 align-items-center bgc-primary-l3 p-2">
                                <div class="col-7 text-right">
                                    Total Amount
                                </div>
                                <div class="col-5">
                                    <span class="text-150 text-success-d3 opacity-2">${{ $invoice->invoice_rate }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <span class="text-secondary-d1 text-105"><strong>Thank you for your business</strong></span>
                        <p>Should you have any enquiries concerning this Invoice, please contact <strong>Saqib Ashraf</strong> on +923201430963</p>
                    </div>

                    <hr />

                    <div class="text-center">
                        <p>5th Floor, Office #245, Aashiana Shopping Center, Main Blvd Gulberg, Block D1 Block D 1 Gulberg III, Lahore, Punjab 54000</p>
                        <p>Tel: +923201430963 E-mail: linktosaqib@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('scripts')
   <script>
        let button = document.querySelector('#button');

        button.addEventListener('click', (event) => {
        window.print();
        });
   </script>
@endsection
@endsection
