import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api', // change to your Laravel API URL
  headers: {
    'Accept': 'application/json',
  },
});

export default api;
