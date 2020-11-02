<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name='viewport' content='width=device-width, initial-scale=1'>

        <title>Field Jobs Tracker</title>
        <style>
        .invoice-view-table {
          margin: 0 auto;
          width: 100%;
          background-color: #FFFFFF;
          border-collapse: collapse;
        }
        .border-wrapper {
          border: solid thin  rgb(221, 221, 221) !important;
          padding: 0;
        }

        .td-styles {
          text-align: center;
          padding: 15px 0;
        }
        .invoice-tr-bg {
          background-color: rgb(219, 234, 249);
          /* border-bottom: solid 2px rgb(189, 209, 228); */
          border-top: solid 3px rgb(247, 249, 250);
        }
        .invoice-row-height {
          padding: 50px 0;
        }
        .line-grey {
          background-color: rgb(242, 242, 242);
        }
        .invoice-tr-odd {
          background-color: rgb(242, 242, 242);
        }
        .invoice-bg-grey {
          background-color: rgb(242, 242, 242);

        }
        .invoice-text-blue {
          color: #213B50;
        }
        .invoice-text-grey {
          color: rgb(107, 107, 107);
        }
        .padding-left {
          padding-left: 20px !important;

        }
        .padding-left-user {
          padding-left: 35px !important;

        }
        .padding-left-invoice {
          padding-left: 20px !important;

        }
        .text-left {
          text-align: left !important;
        }
        .text-right {
          text-align: right;
        }
        .thanks {
          font-weight: bold;
          text-align: left;
        }
        .border-bottom {
          border-bottom: solid thin rgb(221, 221, 221);
        }
        .text-capitalize {
          text-transform: capitalize;
        }
        .align-middle {
          vertical-align: middle;
        }
        .td-width {
          min-width: 175px;
        }


        </style>
        </head>
        <body>

          <table id="invoiceViewAnchor" class="invoice-view-table" border="0" >
            <tr>
              <th colspan="6"></th>
            </tr>
            <tr class="align-middle">
              <td colspan="3" class="padding-left invoice-text-blue text-capitalize">
                @isset($header->outfitName)
                  <h2>{{ $header->outfitName }}</h2>
                @endisset
              </td>
              <td colspan="2"></td>
              <td colspan="3" class="td-width invoice-row-height invoice-text-grey padding-left-user text-capitalize">
                @isset($header->userName)
                  <span>{{ $header->userName }} <br/></span>
                @endisset

                @isset($header->userAddress)
                  <span>{{ $header->userAddress }} <br/></span>
                @endisset

                @isset($header->userCity)
                  <span>{{ $header->userCity }}, {{ $header->userState }} <br/></span>
                @endisset

                @isset($header->userZipcode)
                  <span>{{ $header->userZipcode }} <br/></span>
                @endisset

                @isset($header->userPhoneNumber)
                  @if ($header->userPhoneNumber != 0)
                    <span>{{ $header->userPhoneNumber }}</span>
                  @endif
                @endisset
              </td>
              <td colspan="1"></td>
            </tr>
            <tr class="invoice-bg-grey">
              <td colspan="1" class="padding-left-invoice invoice-text-blue"><h4 class="text-primary">Invoice</h4></td>
              <td colspan="7" ></td>
            </tr>
            <tr class="invoice-tr-bg invoice-text-blue align-middle">
              <td colspan="3" class="padding-left">
                @isset($header->invoice_number)
                  <span>Invoice #: {{ $header->invoice_number }}<br/></span>
                @endisset

                @isset($header->invoice_date)
                  <span>Invoice Date: {{ $header->invoice_date }}</span>
                @endisset
              </td>
              <td colspan="2" class="td-styles invoice-text-blue  text-capitalize">
                @isset($header->clientCompany)
                  <span>{{ $header->clientCompany }} <br/></span>
                @endisset

                @isset($header->clientName)
                  <span>{{ $header->clientName }} <br/></span>
                @endisset

                @isset($header->clientEmail)
                  <span >{{ $header->clientEmail }}</span>
                @endisset
              </td>
              <td colspan="3" class="td-width invoice-text-blue padding-left text-capitalize">
                @isset($header->clientAddress)
                  <span>{{ $header->clientAddress }} <br/></span>
                @endisset

                @isset($header->clientCity)
                  <span>{{ $header->clientCity }}, {{ $header->clientState }}</span>
                @endisset
              </td>
            </tr>
            <tr class="border-bottom text-center">
              <th colspan="1" class="td-styles invoice-text-blue">Item</th>
              <th colspan="3" class="td-styles invoice-text-blue">Description</th>
              <th colspan="1" class="td-styles invoice-text-blue">Unit Cost</th>
              <th colspan="1" class="td-styles invoice-text-blue">Qty</th>
              <th colspan="2" class="td-styles invoice-text-blue border-right">Line Total</th>
            </tr>
            <!-- Invoice transactions tr loop -->
            @foreach ($transactions as $trans)
              @if ($loop->index % 2 == 0)
              <tr  class="invoice-tr-items text-center line-grey border-bottom">
                <td colspan="1" class="td-styles">
                  @if ($trans->item != null)
                    <span>{{ $trans->item }}</span>
                  @else
                    <span>N/A</span>
                  @endif
                </td>
                <td colspan="3" class="td-styles">
                  @if ($trans->description != null)
                    <span>{{ $trans->description }}</span>
                  @else
                    <span>N/A</span>
                  @endif
                </td>
                <td colspan="1" class="td-styles" >${{ $trans->unitCost }}</td>
                <td colspan="1" class="td-styles" >{{ $trans->quantity }}</td>
                <td colspan="2" class="td-styles" >${{ $trans->lineTotal }}</td>
              </tr>
              @else
              <tr  class="invoice-tr-items text-center border-bottom">
                <td colspan="1" class="td-styles margin-left">
                  @if ($trans->item != null)
                    <span>{{ $trans->item }}</span>
                  @else
                    <span>N/A</span>
                  @endif
                </td>
                <td colspan="3" class="td-styles">
                  @if ($trans->description != null)
                    <span>{{ $trans->description }}</span>
                  @else
                    <span>N/A</span>
                  @endif
                </td>
                <td colspan="1" class="td-styles" >${{ $trans->unitCost }}</td>
                <td colspan="1" class="td-styles" >{{ $trans->quantity }}</td>
                <td colspan="2" class="td-styles" >${{ $trans->lineTotal }}</td>
              </tr>
              @endif
            @endforeach
            <tr class="text-right">
              <td colspan="4"></td>
              <td colspan="2" class="text-right ">SubTotal</td>
              <td colspan="2" class="td-styles text-center border-right">${{ $totals->subTotal }}</td>
            </tr>
            <tr class="text-right">
              <td colspan="4"></td>
              <td colspan="2" class="text-right ">Discount</td>
              <td colspan="2" class="td-styles text-center border-right">${{ $totals->discount }}</td>
            </tr>
            <tr class="text-right">
              <td colspan="4" class="td-styles padding-left thanks">Thank you for your business</td>
              <td colspan="2" class="text-right py-2">Tax</td>
              <td colspan="2" class="td-styles text-center border-right">{{ $totals->tax }}%</td>
            </tr>
            <tr class="text-right">
                <td colspan="4" class="text-left text-capitalize padding-left">
                  @isset($header->invoice_pay_msg)
                    <span>{{ $header->invoice_pay_msg }}</span>
                  @endisset
                </td>
              <td colspan="2" class="text-right invoice-bg-grey invoice-text-blue py-2 font-weight-bold">Balance Due</td>
              <td colspan="2" class="td-styles invoice-bg-grey invoice-text-blue text-center font-weight-bold border-right">${{ $totals->total }}</td>
            </tr>
            <tr class="text-right">
              <td colspan="8" class="py-5"></td>
            </tr>
          </table>


        </body>
 </html>
