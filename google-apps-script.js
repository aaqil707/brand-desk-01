const WEBHOOK_URL = 'https://racoon-banter-animate.ngrok-free.dev/pages/api/webhook/sheets-sync.php';

function syncRowToBackend(e) {
  if (!e) { Logger.log("Must be triggered by an edit."); return; }

  const sheet = e.source.getActiveSheet();
  const row = e.range.getRow();
  if (row === 1) return; // skip header

  const lastCol = sheet.getLastColumn();
  const rowData = sheet.getRange(row, 1, 1, lastCol).getValues()[0];

  // Explicit positional mapping — survives header renames in the sheet
  const payload = {
    empId:    String(rowData[0] || '').trim(),  // A
    name:     rowData[1],                        // B
    title:    rowData[2],                        // C
    phone:    String(rowData[3] || '').trim(),  // D — NEW
    email:    rowData[4],                        // E
    linkedin: rowData[5],                        // F
    teamLead: String(rowData[6] || 'no').toLowerCase().trim(), // G
    leadName: rowData[7],                        // H
    rating:   rowData[8],                        // I
    review:   rowData[9],                        // J
  };

  // Don't fire on rows without an empId
  if (!payload.empId) { Logger.log("Row " + row + ": empty empId, skipping."); return; }

  const options = {
    method: 'post',
    contentType: 'application/json',
    payload: JSON.stringify(payload),
    muteHttpExceptions: true,
  };

  try {
    const response = UrlFetchApp.fetch(WEBHOOK_URL, options);
    Logger.log('Row ' + row + ' → ' + response.getResponseCode() + ': ' + response.getContentText());
  } catch (err) {
    Logger.log('Webhook error: ' + err.toString());
  }
}