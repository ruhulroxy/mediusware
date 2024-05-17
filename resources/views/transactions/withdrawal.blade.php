@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Withdrawal') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h5 style="color:darkcyan">Total Withdrawal: {{ $withdrawals->sum('amount') }}</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Transaction Type</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($withdrawals as $withdrawal)
                            <tr>
                                <td>{{ $withdrawal->date }}</td>
                                <td>{{ ucfirst($withdrawal->transaction_type) }}</td>
                                <td>{{ $withdrawal->amount }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br />
                    <form method="POST" action="/withdrawal">
                        @csrf
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>
                        <br />
                        <button type="submit" class="btn btn-primary" style="float: right">Withdrawal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
