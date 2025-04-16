import { ROLE_ADMIN, ROLE_LEADER } from "./constant";

export const getUserRole = () => {
    const userData = localStorage.getItem("userData");
    const user = JSON.parse(userData);

    return user.role;
}

export const credential = () => {
    const userData = localStorage.getItem("userData");

    return JSON.parse(userData);
}

export const isAdmin = () => {
    return getUserRole() == ROLE_ADMIN;
}

export const isAdminOrTeamLeader = () => {
    return getUserRole() == ROLE_ADMIN || getUserRole() == ROLE_LEADER;
}