$models = @("NoteTemplate", "ChatbotSession", "Room", "BedAssignment", "Medication", "InventoryLevel", "DispensingRecord", "Shift", "LeaveRequest", "Equipment", "Announcement", "WhatsappLog", "SurveyResponse", "WebauthnKey", "DoctorPreference", "UserPreference")
foreach ($model in $models) {
    php artisan make:model $model -m -n
}
