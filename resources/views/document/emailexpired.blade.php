@extends('layouts.page')
<style>
    /* Custom styles for the expired email message */
.email-expired-message {
  text-align: center;
  margin: 50px auto;
  max-width: 400px;
  padding: 20px;
  background-color: #f8f8f8;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.email-expired-message h3 {
  font-size: 24px;
  margin-bottom: 10px;
}

.email-expired-message p {
  font-size: 16px;
  margin-bottom: 0;
}

</style>
@section('content')
<div class="email-expired-message">
  <h3>Email link has Expired</h3>
</div>
@endsection

