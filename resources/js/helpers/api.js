import axios from 'axios';

const getApiBaseUrl = () => {
    return document.querySelector('meta[name="api-base-url"]').getAttribute('content')
}
export const deleteClient = (clientId) => {
    const apiBaseUrl = getApiBaseUrl();
    return axios.delete(`${apiBaseUrl}/clients/${clientId}`);
}

export const getClients = () => {
    const apiBaseUrl = getApiBaseUrl();
    return axios.get(`${apiBaseUrl}/clients`);
}

export const deleteBooking = (bookingId) => {
    const apiBaseUrl = getApiBaseUrl();
    return axios.delete(`${apiBaseUrl}/bookings/${bookingId}`);
}

export const getJournals = (clientId) => {
    const apiBaseUrl = getApiBaseUrl();
    return axios.get(`${apiBaseUrl}/clients/${clientId}/journals`);
}

export const createJournal = (clientId, data) => {
    const apiBaseUrl = getApiBaseUrl();
    return axios.post(`${apiBaseUrl}/clients/${clientId}/journals`, data);
}

export const deleteJournal = (clientId, journalId) => {
    const apiBaseUrl = getApiBaseUrl();
    return axios.delete(`${apiBaseUrl}/clients/${clientId}/journals/${journalId}`);
}
