<style>
    table.main td,
    table.main th {
        border: 1px solid;
    }

    td.ft {
        padding: 0px;
    }

    table.main {
        border: 1px solid;
        border-collapse: collapse;
    }

    table.amd {
        width: 100%;
        border-collapse: collapse;
    }

    table.amd td {
        border: none;
        border-bottom: 1px solid !important;
    }

    table.amd tr:nth-last-child(1) td {
        border-bottom: none !important;
    }

    #ul_top_hypers {
        margin-top: 5px;
    }

    #ul_top_hypers li {
        display: inline;
        text-align: center;
    }

</style>
<h4><span>Site/Loctaion:{{ $jobOrder['0']['site_name'] }}({{ $jobOrder['0']['site_code'] }})</span>
    <span>Manual Order #{{ $jobOrder['0']['order_no_manual'] }}</span><br>
    Customer/Country{{ $jobOrder['0']['customer_name'] }}-{{ $jobOrder['0']['country_name'] }}
</h4>
<table class="main">

    <tr>
        <th>#</th>
        <th>Product</th>
        <th>Color</th>
        <th>quantity</th>
        <th>Product Test</th>
        <th>Product Description</th>
        <th>QC Date
        </th>
        <th>Estimate Delivery Date
        </th>
        <th>
            Container Vol.</th>
        <th>PO NO
        </th>
        <th> Truck In</th>

    </tr>



    @if (empty(count($jobOrder)))
        <tr>
            <td colspan="4"><b>No Products Added</b>
            </td>
        </tr>
    @endif

    @foreach ($jobOrder as $jobProduct)
        <tr>
            <td>#{{ $loop->iteration }}</td>

            <td>
                {{ $jobProduct['product_name'] }}
            </td>
            <td class="ft">
                <table class="amd">
                    @foreach ($jobProduct['colors'] as $color)
                        <tr>
                            <td>{{ $color }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
            <td class="ft">
                <table class="amd">
                    @foreach ($jobProduct['quantity'] as $quantity)
                        <tr>
                            <td>{{ $quantity }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>

            <td class="ft">
                <table class="amd">
                    @foreach ($jobProduct['product_test'] as $product_test)
                        <tr>
                            <td>{{ $product_test }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>


            <td>
                {{ $jobProduct['item_description'] }}</td>

            <td class="ft">
                <table class="amd">
                    @foreach ($jobProduct['qc_date'] as $qc_date)
                        <tr>
                            <td>{{ $qc_date }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>

            <td class="ft">
                <table class="amd">
                    @foreach ($jobProduct['crd_date'] as $crd_date)
                        <tr>
                            <td>{{ $crd_date }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>

            <td class="ft">
                <table class="amd">
                    @foreach ($jobProduct['container_vol'] as $container_vol)
                        <tr>
                            <td>{{ $container_vol }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>

            <td class="ft">
                <table class="amd">
                    @foreach ($jobProduct['po_no'] as $po_no)
                        <tr>
                            <td>{{ $po_no }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>


            <td class="ft">
                <table class="amd">
                    @foreach ($jobProduct['truck_in'] as $truck_in)
                        <tr>
                            <td>{{ $truck_in }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>


        </tr>
        <tr>
            <th></th>
            <th colspan="2">Product Picture</th>
            <th colspan="2">Packaging Detail</th>
            <th colspan="6">Packaging Picture</th>

        </tr>
        <tr>
            <td></td>
            <td colspan="2">
                <img src="{{ Url('') . '/uploads/' . $jobProduct['pictureImage'] }}">
            </td>
            <td colspan="2">{!! $jobProduct['packagingDetail'] !!}</td>
            <td colspan="6">
                <ul style="list-style: none;" class="ul_top_hypers mt-2">
                    @foreach ($jobProduct['packingPictures'] as $picture)
                        <li class="picture_{{ $picture['id'] }}">
                            <a href="{{ Url('') }}/uploads/{{ $picture['picture_link'] }}" target="_blank">
                                <img style='width:200px;' src="{{ Url('') }}/uploads/{{ $picture['picture_link'] }}" alt="picture"
                                    class="img-thumbnail">
                            </a>
                        </li>
                    @endforeach
                </ul>
            </td>
        </tr>
    @endforeach


</table>
