$models = @("Department", "Specialty", "MedicalRecord", "Prescription", "PrescriptionItem", "LabOrder", "LabResult", "SoapNote", "Message", "Vital", "Invoice", "Payment", "Review", "Referral", "AuditLog", "Setting")
foreach ($model in $models) {
    Write-Host "Creating $model"
    Start-Process -NoNewWindow -Wait -FilePath "php" -ArgumentList "artisan make:model $model -m -n"
}
