const API_BASE = 'http://your-ip-or-domain.com/api'; // assuming /api is the base path

export async function loginUser(username: string, password: string) {
  const res = await fetch(`${API_BASE}/user/login`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ username, password }),
  });
  return res.json();
}

export async function signupUser(username: string, password: string) {
  const res = await fetch(`${API_BASE}/user/signup`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ username, password }),
  });
  return res.json();
}

export async function fetchListings() {
  const res = await fetch(`${API_BASE}/listing/index`);
  return res.json();
}

export async function createListing(data: {
  listname: string;
  listdes: string;
  listprice: number;
  image: string; // base64 encoded
}) {
  const res = await fetch(`${API_BASE}/listing/create`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data),
  });
  return res.json();
}