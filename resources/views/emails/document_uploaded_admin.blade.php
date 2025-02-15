<!DOCTYPE html>
<html>
<head>
    <title>New Document Uploaded</title>
</head>
<body>
    <h1>New Document Uploaded</h1>
    <p>Dear Admin,</p>
    <p>A new document <strong>{{ $documentName }}</strong> has been uploaded by <strong>{{ $uploadedBy }}</strong> and is waiting for your approval.</p>
    <p>You can review the document <a href="{{ $documentUrl }}">here</a>.</p>
    <p>Please review it at your earliest convenience.</p>
    <p>Thank you!</p>
</body>
</html> 