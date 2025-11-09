import axios from "axios";

// Configura a base da API (puxando do .env ou usando padrão)
const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || "http://localhost:8000/api",
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

// ======================= PACIENTES =======================
export const pacienteService = {
  getAll() {
    return api.get("/pacientes");
  },
  getById(id) {
    return api.get(`/pacientes/${id}`);
  },
  create(data) {
    return api.post("/pacientes", data);
  },
  update(id, data) {
    return api.put(`/pacientes/${id}`, data);
  },
  delete(id) {
    return api.delete(`/pacientes/${id}`);
  },
};

// ======================= ATENDIMENTOS =======================
export const atendimentoService = {
  getAll() {
    return api.get("/atendimentos");
  },
  getById(id) {
    return api.get(`/atendimentos/${id}`);
  },
  create(data) {
    return api.post("/atendimentos", data);
  },
  update(id, data) {
    return api.put(`/atendimentos/${id}`, data);
  },
  delete(id) {
    return api.delete(`/atendimentos/${id}`);
  },
};

export default api;
