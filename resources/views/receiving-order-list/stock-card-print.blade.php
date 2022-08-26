<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Styles -->
  <link href="{{ asset('assets/bootstrap-5.0.1/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/font-awesome-5.15.3/css/all.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/style.css') }}" rel="stylesheet">

  <style>
    @media print {
      .noPrint{
        display:none;
      }
    }
    td {
      padding: 5px;
    }
    </style>
</head>

<body onload="window.print()">
  <div id="app">
    <div class="container">

      <div class="row text-center">
        <div class="col">
          <button type="button" class="btn btn-sm btn-primary noPrint" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
        </div>
      </div>
      
      <div class="row text-center">
        <div class="col">
          <h1>SIMEWOOD PRODUCT SDN BHD</h1>
          <h2>(512211-A)</h2>
          <h3>Store Card</h3>
          <b>NO.:</b> {{ $stockCard->stock_card_number }}
        </div>
      </div>

      <div class="row">
        <div class="col">
          <b>Store</b>
        </div>
        <div class="col">
          <b>White Part</b>
        </div>
        <div class="col">
          <b>Others</b>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <input type="checkbox"> R/ Material
          <br>
          <input type="checkbox"> W/ Material
        </div>
        <div class="col">
          <input type="checkbox"> Repair
          <br>
          <input type="checkbox"> Others
        </div>
        <div class="col">
          <input type="checkbox"> F/ Goods
          <br>
          <input type="checkbox" @if($stockCard->is_rejected) checked @endif> Rejection / Return
        </div>
      </div>

      <div class="row">
        <div class="col">

          <style>
            table td {
              border: 1px solid #333;
            }
            table {
              width: 100%;
            }
          </style>

          <table>
            <tr>
              <td><b>Model</b></td>
              <td colspan="2">{{ $stockCard->product->model_name }} [{{ $stockCard->product->product_name }}]</td>
            </tr>
            <tr>
              <td><b>Job Order/Po No</b></td>
              <td colspan="2">{{ $stockCard->jobOrder->order_no_manual ?? 'N/A' }} / {{ $stockCard->jobOrder->po_no ?? 'N/A' }}</td>
            </tr>
            <tr>
              <td><b>Order Size</b></td>
              <td colspan="2">
                @if(isset($jobPurchaseItem->id) && !empty($jobPurchaseItem->id))
                {{ $jobPurchaseItem->order_length }}x{{ $jobPurchaseItem->order_width }}x{{ $jobPurchaseItem->order_thick }}
                @else
                N/A
                @endif
              </td>
            </tr>
            <tr>
              <td><b>Qty</b></td>
              <td colspan="2">{{ $stockCard->available_quantity }}</td>
            </tr>
            <tr>
              <td><b>Part No & Size</b></td>
              <td colspan="2">{{ $stockCard->product->model_name }} / 
                @if(isset($jobPurchaseItem->id) && !empty($jobPurchaseItem->id))
                {{ $jobPurchaseItem->length }}x{{ $jobPurchaseItem->width }}x{{ $jobPurchaseItem->thick }}
                @else
              N/A
              @endif</td>
            </tr>
            <tr>
              <td><b>Color</b></td>
              <td colspan="2">{{ $stockCard->product->color_name }} {{ $stockCard->product->color_code }}</td>
            </tr>
            <tr>
              <td><b>Date</b></td>
              <td>
                <b>IN:</b> {{ $stockCard->date_in }}
              </td>
              <td>
                <b>OUT:</b> {{ $stockCard->date_out ?? "N/A" }}
              </td>
            </tr>

            <tr>
              <td><b>Remarks</b></td>
              <td colspan="2">
                <br>
                <br>
                <br>
              </td>
            </tr>
          </table>
         
        </div>
      </div>

      <div class="row mt-2">
        <div class="col">
          <h3>Issued By:</h3>
          <h3>Date:</h3>
        </div>
        <div class="col text-end">
          {!! QrCode::size(80)->generate($stockCard->stock_card_number); !!}
          <br>
          {{ $stockCard->stock_card_number }}
        </div>
      </div>

    </div>
  </div>

  <!-- Scripts -->
  <script>
    var base_url = "{{ url('/') }}";
  </script>
  <script src="{{ asset('assets/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('assets/bootstrap-5.0.1/js/bootstrap.bundle.min.js') }}" defer></script>
</body>
</html>