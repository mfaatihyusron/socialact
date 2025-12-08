# Fix Photo Upload Issues in Complaint Form and Report Management

## Information Gathered

- Public complaint form (form pengaduan) uses Web.php lapor_submit method for 'image_before' upload.
- Admin report management uses Content.php resolve_report method for 'image_after' upload.
- Both use CodeIgniter upload library with similar config: path uploads/reports/, allowed types gif|jpg|png|jpeg, max_size 10240KB, encrypt_name TRUE.
- Directory uploads/reports/ exists with test files, but permissions may be insufficient.
- Default images 'default.jpg' and 'default_after.jpg' are missing, causing fallback to Unsplash URLs.
- Error messages exist but may not be visible to users.
- Upload failures are handled by defaulting to 'default.jpg' in Web.php or null in Content.php with warnings.

## Plan

1. Add detailed error logging to upload functions in Web.php and Content.php.
2. Check and set proper permissions for uploads/reports/ directory.
3. Create default placeholder images if missing.
4. Improve error visibility to users in views.
5. Test uploads with different scenarios.

## Dependent Files to be edited

- application/controllers/Web.php: Enhanced lapor_submit upload logic with logging and better error messages.
- application/controllers/Content.php: Enhanced resolve_report upload logic with logging and better error messages.
- application/views/content/lapor.php: Improve error display.
- application/views/admin/content.php: Improve error display.
- uploads/reports/: Ensure permissions and add default images.

## Followup steps

- Test public complaint form upload.
- Test admin report resolve upload.
- Verify error messages are shown.
- Check logs for any issues.

## Completed Steps

- [x] Added logging and better error messages to Web.php lapor_submit.
- [x] Added logging and better error messages to Content.php resolve_report.
- [x] Attempted to set permissions (command failed, may need manual intervention).
- [x] Created placeholder default.jpg (text file, needs actual image).
