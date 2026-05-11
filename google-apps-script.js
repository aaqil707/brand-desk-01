/**
 * Google Apps Script for Bi-Directional Sync
 * 
 * Installation:
 * 1. Open your Google Sheet.
 * 2. Go to Extensions -> Apps Script.
 * 3. Paste the following code.
 * 4. Replace WEBHOOK_URL with your actual PHP webhook URL: 
 *    (e.g., https://yourdomain.com/api/webhook/sheets-sync.php)
 * 5. Deploy as Web App:
 *    - Deploy -> New Deployment
 *    - Select type: Web App
 *    - Execute as: Me
 *    - Who has access: Anyone
 * 6. Copy the Web App URL and paste it into the PHP save_profile.php file.
 */

const WEBHOOK_URL = 'https://yourdomain.com/api/webhook/sheets-sync.php';

/**
 * Trigger function that runs on every edit in the sheet.
 */
function onEdit(e) {
  const sheet = e.source.getActiveSheet();
  const range = e.range;
  const row = range.getRow();
  const col = range.getColumn();
  
  // Only sync if the edit is not in the header row
  if (row === 1) return;

  // Get all data for the edited row
  const rowData = sheet.getRange(row, 1, 1, sheet.getLastColumn()).getValues()[0];
  const headers = sheet.getRange(1, 1, 1, sheet.getLastColumn()).getValues()[0];

  // Map headers to row values
  const payload = {};
  headers.forEach((header, index) => {
    payload[header] = rowData[index];
  });

  // Dispatch payload to PHP Webhook
  try {
    const options = {
      method: 'post',
      contentType: 'application/json',
      payload: JSON.stringify(payload),
      muteHttpExceptions: true
    };
    UrlFetchApp.fetch(WEBHOOK_URL, options);
  } catch (err) {
    console.error('Failed to sync to PHP webhook: ' + err.toString());
  }
}

/**
 * Handler for incoming POST requests from PHP (Outbound Sync)
 */
function doPost(e) {
  const data = JSON.parse(e.postData.contents);
  const sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();
  const values = sheet.getDataRange().getValues();
  const headers = values[0];
  
  // Find the row by ID (assuming ID is in the first column or a column named 'id')
  const idIndex = headers.indexOf('id');
  if (idIndex === -1) return ContentService.createTextOutput('ID column not found').setMimeType(ContentService.MimeType.TEXT);

  let rowIndex = -1;
  for (let i = 1; i < values.length; i++) {
    if (values[i][idIndex] == data.id) {
      rowIndex = i + 1;
      break;
    }
  }

  if (rowIndex === -1) {
    // Append new row if not found
    const newRow = headers.map(header => data[header] || '');
    sheet.appendRow(newRow);
  } else {
    // Update existing row
    headers.forEach((header, index) => {
      if (data[header] !== undefined) {
        sheet.getRange(rowIndex, index + 1).setValue(data[header]);
      }
    });
  }

  return ContentService.createTextOutput(JSON.stringify({status: 'success'}))
    .setMimeType(ContentService.MimeType.JSON);
}
