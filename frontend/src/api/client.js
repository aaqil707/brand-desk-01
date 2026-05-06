/**
 * API Configuration & HTTP Client
 * Centralizes all API calls to the PHP backend.
 */

const API_BASE = '/api';

class ApiError extends Error {
  constructor(message, status) {
    super(message);
    this.status = status;
    this.name = 'ApiError';
  }
}

async function request(endpoint, options = {}) {
  const url = `${API_BASE}${endpoint}`;
  const config = {
    credentials: 'include',
    ...options,
  };

  try {
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), 8000); // 8 second timeout
    
    const response = await fetch(url, { ...config, signal: controller.signal });
    clearTimeout(timeout);
    
    if (!response.ok) {
      const text = await response.text().catch(() => 'Unknown error');
      throw new ApiError(text, response.status);
    }

    const contentType = response.headers.get('content-type');
    if (contentType && contentType.includes('application/json')) {
      return response.json();
    }
    return response.text();
  } catch (err) {
    // If backend is unavailable, return mock response
    if (err.name === 'AbortError' || !err.status) {
      console.warn('Backend unavailable, using mock response for:', endpoint);
      return getMockResponse(endpoint);
    }
    throw err;
  }
}

function getMockResponse(endpoint) {
  const mocks = {
    '/check_session.php': { loggedIn: false, user: null },
    '/login.php': { success: false, message: 'Backend unavailable' },
    '/register.php': { success: false, message: 'Backend unavailable' },
    '/logout.php': { success: true },
  };
  return mocks[endpoint] || { success: false, message: 'Unknown endpoint' };
}

// ── Auth API ──
export const authApi = {
  login(email, password) {
    const formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);
    return request('/login.php', { method: 'POST', body: formData });
  },

  register(name, email, password) {
    const formData = new FormData();
    formData.append('name', name);
    formData.append('email', email);
    formData.append('password', password);
    return request('/register.php', { method: 'POST', body: formData });
  },

  logout() {
    return request('/logout.php');
  },

  checkSession() {
    return request('/check_session.php');
  },
};

// ── Users API ──
export const usersApi = {
  getAll() {
    return request('/get_users.php');
  },

  getById(id) {
    return request(`/get_user.php?id=${id}`);
  },

  add(userData) {
    const formData = new FormData();
    Object.entries(userData).forEach(([key, value]) => {
      formData.append(key, value);
    });
    return request('/add_user.php', { method: 'POST', body: formData });
  },

  update(userData) {
    const formData = new FormData();
    Object.entries(userData).forEach(([key, value]) => {
      formData.append(key, value);
    });
    return request('/update_user.php', { method: 'POST', body: formData });
  },

  delete(id) {
    const formData = new URLSearchParams();
    formData.append('id', id);
    return request('/delete_user.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: formData,
    });
  },
};

// ── Upload API ──
export const uploadApi = {
  uploadImages(files, onProgress) {
    return new Promise((resolve, reject) => {
      const formData = new FormData();
      files.forEach(file => formData.append('images[]', file));

      const xhr = new XMLHttpRequest();
      xhr.open('POST', '/Pages/upload.php');
      xhr.withCredentials = true;

      if (onProgress) {
        xhr.upload.addEventListener('progress', (e) => {
          if (e.lengthComputable) {
            onProgress(Math.round((e.loaded / e.total) * 100));
          }
        });
      }

      xhr.onload = () => {
        if (xhr.status === 200) {
          try {
            resolve(JSON.parse(xhr.responseText));
          } catch {
            resolve(xhr.responseText);
          }
        } else {
          reject(new ApiError('Upload failed', xhr.status));
        }
      };

      xhr.onerror = () => reject(new ApiError('Network error', 0));
      xhr.send(formData);
    });
  },
};

// ── Profile API ──
export const profileApi = {
  saveProfile(profileData) {
    return request('/save_profile.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(profileData),
    });
  },

  getProfile(id) {
    return request(`/get_profile.php?id=${id}`);
  },

  getUserProfiles() {
    return request('/get_user_profiles.php');
  },

  updateReview(id, reviews) {
    return request('/update_review.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ id, reviews }),
    });
  },

  async fetchGoogleSheetData(empId) {
    const url = `https://docs.google.com/spreadsheets/d/1d_WRPltqOlzT55bx-tNs0qvd-t9RB9EAeTTsp8m8HdM/gviz/tq?tqx=out:json&gid=1611340410&headers=1`;
    const response = await fetch(url);
    if (!response.ok) throw new Error('Failed to fetch from Google Sheets.');
    const textResponse = await response.text();
    const jsonString = textResponse.substring(textResponse.indexOf('{'), textResponse.lastIndexOf('}') + 1);
    const data = JSON.parse(jsonString);

    if (!data.table || !data.table.rows) throw new Error('Unexpected data format from Google Sheets.');

    const rows = data.table.rows;
    for (let i = 0; i < rows.length; i++) {
      const row = rows[i];
      if (!row || !row.c) continue;
      const cellValue = row.c[0]?.v || row.c[0]?.f || row.c[0];
      if (cellValue !== null && String(cellValue).trim().toLowerCase() === String(empId).trim().toLowerCase()) {
        const getCellValue = (c) => (c && (c.v !== null && c.v !== undefined ? c.v : (c.f !== null && c.f !== undefined ? c.f : '')));
        return {
          name: getCellValue(row.c[1]),
          title: getCellValue(row.c[2]),
          email: getCellValue(row.c[3]),
          linkedin: getCellValue(row.c[4]),
          teamLead: getCellValue(row.c[5]),
          leadName: getCellValue(row.c[6]),
          ratings: getCellValue(row.c[7]),
          reviews: getCellValue(row.c[8]),
        };
      }
    }
    throw new Error(`Employee ID "${empId}" not found in the sheet.`);
  },
};

