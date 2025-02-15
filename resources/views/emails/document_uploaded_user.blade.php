<!DOCTYPE html>
<html>
<head>
    <title>Document Uploaded</title>
</head>
<body>
    @if($status == 'Pending')
        <h1>Your Document Has Been Uploaded</h1>
        <p>Dear User,</p>
        <p>Your document <strong>{{ $documentName }}</strong> has been successfully uploaded and is currently awaiting approval.</p>
    @elseif($status == 'Approved')
        <h1>Your Document Has Been Approved</h1>
        <p>Dear User,</p>
        <p>Your document <strong>{{ $documentName }}</strong> has been approved.</p>
    @elseif($status == 'Rejected')
        <h1>Your Document Has Been Rejected</h1>
        <p>Dear User,</p>
        <p>Your document <strong>{{ $documentName }}</strong> has been rejected.</p>
        <p>Please upload the document again.</p>
    @endif
    <p>You can view your document <a href="{{ $documentUrl }}">here</a>.</p>
    <p>Thank you!</p>
</body>
</html>    