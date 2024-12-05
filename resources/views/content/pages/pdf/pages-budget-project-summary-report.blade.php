<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Local Purchase Order</title>
    <style>
        .header {
            color: #000;
            padding: 20px;
            border-bottom: solid 3px #000;
            /* Adjust color and size as needed */
        }

        .header-top .logo {
            width: 200px;
            /* Adjust size as needed */
            height: auto;
        }

        .header-top .department {
            font-size: 20px;
            font-weight: bold;
            text-align: right;
        }

        .ref-number {
            text-align: right;
            color: grey;
        }

        .header-bottom {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="header-top">
            <div>
                <img src="https://tasks.xad.ae/files/system/_file65bbfe35defa7-site-logo.png" alt="Xad Technologies LLC Logo" class="logo">
            </div>
        </div>
    </div>
    <div class="header-bottom" style="font-family: Arial, sans-serif; text-align: center; width: 100%;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="text-align: left;  width: 50%;">
                    <b>Xad Technologies LLC </b>
                </td>

                <td style="border: 1px solid #000; padding: 8px;  width: 50%;">Date: {{$purchaseOrder->date}}</td>
            </tr>
            <tr>
                <td svtyle="text-align: left; font-size: 13px;  width: 50%;">
                    <span>Office 1308, Opal Tower Business Bay, Dubai</span>
                </td>
                <td style="border: 1px solid #000; padding: 8px;  width: 50%;">Purchase Order: {{$purchaseOrder->po_number}}</td>
            </tr>
            <tr>
                <td svtyle="text-align: left; font-size: 13px;   width: 50%;">
                    <span> TRN: 100293391400003</span><br>
                    <span> Email: admin@xadtech.com</span><br>
                </td>
                <td style="border: 1px solid #000; padding: 8px;  width: 50%;">Payment Terms: {{$purchaseOrder->payment_term}}</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: left;  width: 50%;">
                    <span> Mobile: 054-7104301</span><br>
                    <span> Website: www.xadtechnologies.com</span>
                </td>

            </tr>
        </table>
    </div>
    <div class="header-bottom" style="font-family: Arial, sans-serif; text-align: center; width: 100%;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="text-align: center;border: 1px solid #000;font-weight: bold; padding: 8px; width: 50%;">
                    <b>Supplier Detail</b>
                </td>

                <td style="text-align: center;border: 1px solid #000;font-weight: bold; padding: 8px;  width: 50%;">Project Detail</td>

            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 8px;  width: 50%;">
                    <b>Name:</b>{{$purchaseOrder->supplier_name}}
                </td>
                <td style="border: 1px solid #000; padding: 8px;  width: 30%;"><b>Project:</b> {{$budget->reference_code}}</td>

            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 8px;  width: 50%;">
                    <b>Address:</b>{{$purchaseOrder->supplier_address}}
                </td>
                <td style="border: 1px solid #000; padding: 8px;  width: 50%;"><b>Requested By:</b> {{$requested->first_name}}</td>

            </tr>
            <tr>
                <td style="text-align: left;  padding: 8px; width: 50%;">

                </td>
                <td style="border: 1px solid #000; padding: 8px;  width: 50%;">
                    <b> Verified By:</b> {{ $prepared->verified_by ?? 'not verified' }}
                </td>

            </tr>
            <tr>
                <td style="text-align: left;  padding: 8px; width: 50%;">

                </td>
                <td style="border: 1px solid #000; padding: 8px;  width: 50%;"><b>Prepared By:</b> {{$prepared->first_name}}</td>

            </tr>


        </table>
    </div>
    <table style="width: 100%; font-size: 12px; border-collapse: collapse; margin-top: 10px">
        <tr>
            <td style="border: 1px solid #000; padding: 8px;  width: 20%;">ITEM # :</td>
            <td style="border: 1px solid #000; padding: 8px; width: 50%;">DESCRIPTION OF GOODS</td>
            <td style="border: 1px solid #000; padding: 8px; width: 50%;">QTY</td>
            <td style="border: 1px solid #000; padding: 8px; width: 50%;">UNIT PRICE</td>
            <td style="border: 1px solid #000; padding: 8px; width: 50%;">TOTAL</td>

        </tr>
        @foreach (@$purchaseOrder->items as $items)
        @foreach (json_decode($items->items) as $item)
        <tr>
            <td style="border: 1px solid #000; padding: 8px;width: 50%;">{{@$item->item}}</td>
            <td style="border: 1px solid #000; padding: 8px;width: 20%;">{{@$item->description}}</td>
            <td style="border: 1px solid #000; padding: 8px;width: 50%;">{{@$item->quantity}} </td>
            <td style="border: 1px solid #000; padding: 8px;width: 20%;">{{@$item->unitPrice}}</td>
            <td style="border: 1px solid #000; padding: 8px;width: 20%;">{{@$item->itemTotal}}</td>
        </tr>
        @endforeach
        @endforeach




    </table>



    <table>
        <tr>
            <td><b>Terms And Conditions: As Agreed with supplier (Mention in the provided Quotation)</b></td>
        </tr>
    </table>
    <div class="header-container" style="display: flex; justify-content: space-between; font-family: Arial, sans-serif; width: 100%; gap: 20px;">

        <!-- First Table -->
        <div class="table-container" style="flex: 1;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="text-align: left; padding: 8px; width: 50%;"></td>
                    <td style="border: 1px solid #000; padding: 8px; width: 50%;">VAT: {{$purchaseOrderItem->total_vat ?? 0 }}</td>
                </tr>
                <tr>
                    <td style="text-align: left; padding: 8px; width: 50%;"></td>
                    <td style="border: 1px solid #000; padding: 8px; width: 50%;">Total Discount: {{$purchaseOrderItem->total_discount ?? 0}}</td>
                </tr>
                <tr>
                    <td style="text-align: left; padding: 8px; width: 50%;"></td>
                    <td style="border: 1px solid #000; padding: 8px; width: 50%;">Delivery Charges: {{$purchaseOrderItem->delivery_charges ?? 0}}</td>
                </tr>
            </table>
        </div>
    
        <!-- Second Table -->
        <div class="table-container" style="flex: 1;">
            <table style="width: 100%; border-collapse: collapse; margin-top:10px">
                <tr>
                    <td style="text-align: left; padding: 8px; width: 70%;"></td>
                    <td colspan="2" style="text-align: center; border: 1px solid #000; font-weight: bold; padding: 8px; width: 30%;">Budget Department Verification</td>
                </tr>
                <tr>
                    <td style="text-align: left; padding: 8px; width: 70%;"></td>
                    <td colspan="2" style="border: 1px solid #000; padding: 8px; width: 30%;"><b>Total Budget:</b> 
                        @if(is_null($purchaseOrderItem->allocated_budget_amount))
                            <span style="color: red; font-weight: bold;">Not Assigned</span>
                        @else
                            {{ number_format($purchaseOrderItem->allocated_budget_amount) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left; padding: 8px; width: 70%;"></td>
                    <td colspan="2" style="border: 1px solid #000; padding: 8px; width: 30%;"><b>Utilization:</b> {{number_format($purchaseOrderItem->budget_utilization)}}</td>
                </tr>
                <tr>
                    <td style="text-align: left; padding: 8px; width: 70%;"></td>
                    <td colspan="2" style="border: 1px solid #000; padding: 8px; width: 30%;"><b>Balance Budget:</b> {{number_format($purchaseOrderItem->balance_budget)}}</td>
                </tr>
                <tr>
                    <td style="text-align: left; padding: 8px; width: 70%;"></td>
                    <td colspan="2" style="border: 1px solid #000; padding: 8px; width: 30%;"><b>Current Request:</b> {{number_format($purchaseOrderItem->amount_requested)}}</td>
                </tr>
                <tr>
                    <td style="text-align: left; padding: 8px; width: 70%;"></td>
                    <td colspan="2" style="border: 1px solid #000; padding: 8px; width: 30%;"><b>Balance:</b> {{ number_format($purchaseOrderItem->total_balance) }}</td>
                </tr>
                <tr>
                    <td style="text-align: left; padding: 8px; width: 70%;"><hr>Approved By: Chief Executive Officer</td>
                    <td style="border: 1px solid #000; padding: 8px; width: 50%;"><b>Name & Signature:</b> {{$prepared->first_name}}</td>
                    <td style="border: 1px solid #000; padding: 8px; width: 50%;"><b>Date:</b>  {{$purchaseOrder->date}}</td>
                </tr>
            </table>
        </div>
    
    </div>
    
</body>

</html>