<x-layouts.app>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>User List</h1>
            </div>
        </div>

        <!-- Tabella principale con informazioni sugli utenti -->
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Account Balance</th>
                            <th>Transactions</th>
                            <th>Credit Card Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($financialData->users as $user)
                        <tr>
                            <td>{{ $user->user_id }}</td>
                            <td>{{ $user->username }}</td>
                            <td>${{ number_format($user->account_balance, 2) }}</td>
                            <td>
                                <ul>
                                    @foreach ($user->transactions as $transaction)
                                    <li>
                                        <strong>{{ $transaction->transaction_id }}</strong><br>
                                        <span>{{ $transaction->date }} - ${{ number_format($transaction->amount, 2) }}<br>
                                        <em>{{ $transaction->description }}</em></span>
                                    </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <strong>Card Number:</strong> **** **** **** {{ substr($user->credit_card->card_number, -4) }}<br>
                                <strong>Expiry Date:</strong> {{ $user->credit_card->expiry_date }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
