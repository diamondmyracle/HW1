const API_BASE = 'http://129.133.75.93'; // change if using physical device or deployed backend

// Login user
export async function loginUser(username: string, password: string) {
    const obj: {username: string; psw: string} = {
        username: username,
        psw: password,
      } ;
  const res = await fetch(`${API_BASE}/login.php/user/login`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(obj),
  });
  return res.json();
}

// Signup user
export async function signupUser(username: string, password: string) {
  const obj: {username: string; psw: string} = {
    username: username,
    psw: password,
  } ;

  const res = await fetch(`${API_BASE}/signup.php/user/create`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(obj),
  });
  return res.json();
}

// Fetch all listings
export async function fetchListings() {
  const res = await fetch(`${API_BASE}/listings.php`);
  return res.json();
}

// Create a new listing (no image)
export async function createListing(data: {
  listname: string;
  listdes: string;
  listprice: number;
}) {
  const res = await fetch(`${API_BASE}/newlisting.php`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data),
  });
  return res.json();
}

// Fetch all users
export async function fetchUsers() {
  const res = await fetch(`${API_BASE}/signup.php`);
  return res.json();
}
