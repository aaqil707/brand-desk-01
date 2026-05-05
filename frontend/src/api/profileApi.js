/**
 * ProfileGenerator API — Handles upload and image generation via PHP backend.
 */

const API_BASE = '/api';

export async function generateProfile(file, entity, uploadMethod = 'manual', employeeId = '') {
  const formData = new FormData();
  formData.append('entity', entity);
  formData.append('uploadMethod', uploadMethod);

  if (uploadMethod === 'employee') {
    formData.append('employeeId', employeeId);
  } else {
    formData.append('portrait', file);
  }

  const response = await fetch(`${API_BASE}/generate_profile.php`, {
    method: 'POST',
    credentials: 'include',
    body: formData,
  });

  const responseText = await response.text();

  try {
    const data = JSON.parse(responseText);
    if (!response.ok) {
      throw new Error(data.message || `Server error: ${response.status}`);
    }
    return data;
  } catch (error) {
    console.error("Failed to parse JSON. Raw server response:", responseText);
    if (error instanceof SyntaxError) {
      throw new Error("The server encountered an error and returned an invalid response. Check console for details.");
    }
    throw error;
  }
}
