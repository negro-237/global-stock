<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
            .header { text-align: center; }
            .header img { width: 280px; }
            .info { margin-top: 10px;}
            .title-box {
                border: 1px solid #000;
                width: 200px;
                padding: 10px;
                font-weight: bold;
            }
            .items table { width: 100%; border-collapse: collapse; margin-top: 15px; }
            .items table th, table td {
                border: 1px solid #000;
                padding: 5px;
                text-align: left;
            }
            .empty {
              border: none;
              width: 30%;
            }
            .right { text-align: right; }
            .total { font-size: 14px; font-weight: bold; }
            .prefooter { margin-top: 50px; text-align: center; }
            .footer { position: fixed; bottom: 0; width: 100%; }
            .separator { border: none; border-top: 2px solid #119167ff; }
            .address { font-size: 15px; margin-top: 5px; }
            .uppercase { text-transform: uppercase; }
        </style>
    </head>
    <body>

        <div class="header">
            <img src="{{ public_path('images/logo-1.png') }}" alt="logo">
            <div class="address">
                Distribution des outils de fabrication, de ménuserie, de soudure, <br>d'électricité,
                de boulonnerie, de manutention, de robinetterie, etc... <br>
                Import - Export - Prestations diverses <br>
                M102316147996D
            </div>
        </div>

        <div>
           <hr class="separator">
        </div>

        <table class="info" style="width: 100%">
            <tr>
                <td class="empty"></td><td class="empty"></td>
                <td class="empty">Douala le {{ $order->created_at->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="empty"></td><td class="empty"></td>
                <td class="empty">
                    <strong>Doit :</strong> <strong class="uppercase">{{ $order->customer->name }}</strong>
                </td>
            </tr>
        </table>

        <br>

        <div class="title-box">
            PROFORMA<br>
            {{ $order->id }} / {{ $order->created_at->format('Y') }}
        </div>

        <div class="items">
            <table>
                <thead>
                    <tr>
                        <th>ITEM</th>
                        <th>DESIGNATION</th>
                        <th>QTES</th>
                        <th>P.U</th>
                        <th>P.T</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->transactions as $index => $trx)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $trx->product->name }}</td>
                        <td>{{ $trx->quantity }}</td>
                        <td>{{ number_format($trx->product->price, 0, ',', ' ') }}</td>
                        <td>{{ number_format($trx->product->price * $trx->quantity, 0, ',', ' ') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br>

        <div class="right total">
            TOTAL : {{ $order->amount }} FCFA
        </div>

        <div class="prefooter" style="text-align: right; padding-right: 10%">
            <br><br><br><br><br>
            <strong>La Direction</strong><br>
        </div>

        <div class="footer">
            <hr class="separator">
            <div style="text-align: center; font-size: 10px;">
               generelservices620@gmail.com R C/DLA/2023/B6324 TEL: 694 662 913 Whatsapp: 679 138 637
            </div>
        </div>

    </body>
</html>
