// Auth for account in Field jobs tracker.
// Used in components
export function register(credentials) {
  return new Promise((res, rej) => {
    axios.post('/api/auth/register', credentials)
    .then((response) => {
        res(response.data);
    })
    .catch((err) => {
        rej(err);
    })
  })
}
export function login(credentials) {
  return new Promise((res, rej) => {
    axios.post('/api/auth/login', credentials)
    .then((response) => {
        res(response.data);
    })
    .catch((err) => {
        rej('Wrong email or password');
    })
  })
}
export function logout(token, credentials){
  return new Promise((res, rej) => {
    axios.get('/api/auth/logout', {credentials},{
      headers: {
          Authorization: 'Bearer ' + token
        }
    }).then((response) => {
      res(response.data);
    })
    .catch((err) => {
      console.log('can not log out at this time');
      rej('Can not log out at this time');
    })
  })
}
export function adminLogout(token, credentials){
  return new Promise((res, rej) => {
    axios.get('/admin/logout', {credentials}
    ).then((response) => {
      res(response.data);
    })
    .catch((err) => {
      console.log('can not log out at this time');
      rej('Can not log out at this time');
    })
  })
}
export function getLocalUser(){
  const userStr = localStorage.getItem("user");
  if(!userStr) {
    return null;
  }
  return JSON.parse(userStr);
}

export function getLocalNavHeaderView(){
  const navHeaderStr = localStorage.getItem("navHeaderView");
  if(!navHeaderStr || navHeaderStr == "undefined") {
    return null;
  }
  return JSON.parse(navHeaderStr);
}

// Dashboard Admin
export function getLocalAdmin(){
  let adminStr = localStorage.getItem("admin");
    // adminStr =  localStorage.removeItem("admin");
  console.log(adminStr);
  if(!adminStr) {
    return null;
  }
    // return null;
    return JSON.parse(adminStr);
}

// Dashboard Admin
export function getLocalInDashboard(){
  let inDashboardStr = localStorage.getItem("inDashboard");
  console.log('auth ' + inDashboardStr);
    return JSON.parse(inDashboardStr);;
}
