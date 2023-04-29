<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{$invoice_no}}</title>
</head>
<style type="text/css">
    @font-face {
        font-family: "Inter";
        src: url(<?php echo storage_path("fonts/Inter-Regular.ttf"); ?>) format("truetype");
        font-weight: 400;
        font-style: normal;
    }

    @font-face {
        font-family: "Inter";
        src: url(<?php echo storage_path("fonts/Inter-Bold.ttf"); ?>) format("truetype");
        font-weight: 700;
        font-style: bold;
    }

    @font-face {
        font-family: "Inter";
        src: url(<?php echo storage_path("fonts/Inter-ExtraBold.ttf"); ?>) format("truetype");
        font-weight: 800;

    }

    body {
        font-family: "Inter";
        font-weight: 400;
    }

    .page {
        margin: 0 auto;
        width: 100%;
    }

    * {
        margin: 0;
        padding: 0;
    }

    .float-left {
        float: left;
    }

    .float-right {
        float: right;
    }

    .d-inline {
        display: inline;
    }

    table {
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid;
        padding: 5px;
        text-align: center;
    }
</style>

<body>
    <div class="page">
        <div style="padding:15pt;">
            <div style="display: inline-block;">
                <img src="{{public_path('/images/logo.png')}}" style="height:26px;">
                <p style="font-size:8pt;">E-commerce Platform In Bangladesh!</p>
            </div>
            <div style="display: inline-block;" class="float-right">
                <p style="font-size:9pt;">contact@jenexmart.com</p>
                <p style="font-size:9pt;">Helpline : 01515-200545</p>
            </div>
            <div style="text-align: center; margin-top: 15pt; padding-bottom: 5px; margin-bottom:20pt; background:#111723;">
                <strong style="font-weight: 800; font-size: 16pt; color:white;">INVOICE</strong>
            </div>
            <div style="display:inline-block; margin-top:40px;  width: 100%;">
                <div style="display: inline-block; width: 300px; vertical-align: top; margin-right: 5pt;">
                    <strong style="font-size: 10pt; font-weight: 700;">Shipping Details</strong>
                    <p style="font-size:9pt; margin-top: 1pt;">Name : {{$order_info['receiver_name']}}</p>
                    <p style=" font-size:9pt; margin-top: 1pt;">Address : {{$order_info['receiver_address']}}</p>
                    <p style="font-size:9pt; margin-top: 1pt;">Contact : {{$order_info['receiver_contact_no']}}</p>
                    <p style="font-size:9pt; margin-top: 1pt;">Delivery Charge : {{$order_info['total_delivery_charge']}} BDT</p>
                </div>
                <div style="display: inline-block; text-align: center;">
                    <img style="height: 96pt;" src="data:image/png;base64, {!! $qrcode !!}">
                </div>
                <div style="display: inline-block; vertical-align: top;" class="float-right">
                    <p style="font-size:9pt; margin-top: 1pt;">Invoice No : {{$invoice_no}}</p>
                    <p style="font-size:9pt; margin-top: 1pt;">Invoice Date : {{$invoice_date}}</p>
                    <p style="font-size:9pt; margin-top: 1pt;">Order No : {{$order_info['order_no']}}</p>
                    <p style="font-size:9pt; margin-top: 1pt;">Order Date : {{$order_date}}</p>
                    <p style="font-size:9pt; margin-top: 1pt;">Sold By : {{$items[0]->seller_company_name }}</p>
                </div>
            </div>
            <table style="margin-top:20pt;">
                <tr>
                    <th style="font-size: 8pt; font-weight: 700;">Item</th>
                    <th style="font-size: 8pt; font-weight: 700;">Item SKU</th>
                    <th style="font-size: 8pt; font-weight: 700;">Price</th>
                    <th style="font-size: 8pt; font-weight: 700;">Qty</th>
                    <th style="font-size: 8pt; font-weight: 700;">Delivery Charge</th>
                    <th style="font-size: 8pt; font-weight: 700;">Total Price</th>
                </tr>
                <?php $grand_total_amount = 0; ?>
                @foreach($items as $item)
                <tr style="font-size: 8pt;">
                    <td style="text-align: left; width:380px;">
                        {{ $item->product_variant_title ? $item->product_title . ' - ' .$item->product_variant_title : $item->product_title }}
                        @if($item->size) |
                        Size : {{$item->size}}
                        @endif
                        @if($item->color) |
                        Color : {{$item->color}}
                        @endif
                        @if($item->material) |
                        Material : {{$item->material}}
                        @endif
                    </td>
                    <td>
                        {{$item->sku}}
                    </td>
                    <td>
                        {{number_format($item->price)}}
                    </td>
                    <td>
                        {{number_format($item->quantity)}}
                    </td>
                    <td>
                        {{number_format($item->delivery_charge)}}
                    </td>
                    <td style="width: 60pt;">
                        {{number_format($item->quantity * $item->price)}}
                    </td>
                </tr>
                <?php $grand_total_amount += $item->quantity * $item->price; ?>
                @endforeach

                <tr style="font-size: 8pt;">
                    <td colspan="5" style="text-align:right; font-weight: 700;">
                        Grand Total =
                    </td>
                    <td>
                        {{number_format($grand_total_amount)}} BDT
                    </td>
                </tr>
                <tr style="font-size: 8pt;">
                    <td colspan="5" style="text-align:right; font-weight: 700;">
                        Grand Total + Delivery Charge =
                    </td>
                    <td>
                        {{number_format($grand_total_amount + $order_info['total_delivery_charge'])}} BDT
                    </td>
                </tr>
            </table>

            <div style="margin-top: 20pt;">
                <p style="font-weight: 700; font-size: 9pt;">Terms & Conditions :</p>
                <p style="font-size: 8pt; margin-top: 2pt;">
                <ul style="font-size: 8pt; margin-left:10px;">
                    <li>Please check the product in front of delivery man. </li>
                    <li>Please keep your invoice for any further issues. </li>
                    <li>If product has issues, return the product to the delivery man & give us feedback. </li>
                    <li>Upon returning the product give delivery man the delivery charge. </li>
                    <li>Upon receiving the product, click the received button. Give us rating & review. </li>
                    <li>If there is any replacement policy in the product then that rule will be followed. </li>
                    <li> The authority isn’t responsible for any product issues. </li>
                </ul>
                <span style="font-size: 8pt;">*** Happy Shopping! ***</span>
                </p>
            </div>
        </div>
    </div>
</body>

</html>