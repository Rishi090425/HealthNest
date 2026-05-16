$models = @("Dependent", "SymptomCheck", "Certificate", "NoteTemplate", "ChatbotSession", "Room", "BedAssignment", "Medication", "InventoryLevel", "DispensingRecord", "Shift", "LeaveRequest", "Equipment", "Announcement", "WhatsappLog", "SurveyResponse", "WebauthnKey", "DoctorPreference", "UserPreference")
foreach ($model in $models) {
    Write-Host "Creating $model"
    Start-Process -NoNewWindow -Wait -FilePath "php" -ArgumentList "artisan make:model $model -m"
}
