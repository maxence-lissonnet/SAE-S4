import { requestGET, requestDELETE } from "./ajax.js";
import { showLoader, hideLoader } from "./loader.js";
import { toast } from "./toaster.js";

const table = document.getElementById("table");
const tbody = document.getElementById("tbody");
const emptyState = document.getElementById("empty_state");
const eventSearch = document.getElementById("eventSearch");
const userSearch = document.getElementById("userSearch");

let defaultData = [];

function formatDate(value) {
    if (!value) {
        return "";
    }

    return value.split(" ")[0];
}

function formatPayment(value) {
    switch (value) {
        case "carte_credit":
            return "Carte";
        case "paypal":
            return "PayPal";
        case "especes":
            return "Especes";
        default:
            return value || "-";
    }
}

function createCell(content) {
    const cell = document.createElement("td");
    cell.textContent = content;
    return cell;
}

function loadData() {
    tbody.innerHTML = "";

    const eventTerm = eventSearch.value.trim().toUpperCase();
    const userTerm = userSearch.value.trim().toUpperCase();

    const data = defaultData.filter((item) => {
        const eventLabel = item.nom_evenement.toUpperCase();
        const userLabel = `${item.nom_membre} ${item.prenom_membre}`.toUpperCase();

        return eventLabel.includes(eventTerm) && userLabel.includes(userTerm);
    });

    table.hidden = data.length === 0;
    emptyState.hidden = data.length !== 0;

    data.forEach((item) => {
        const row = document.createElement("tr");

        row.appendChild(createCell(item.nom_evenement));
        row.appendChild(createCell(formatDate(item.date_evenement)));
        row.appendChild(createCell(`${item.nom_membre.toUpperCase()} ${item.prenom_membre}`));
        row.appendChild(createCell(item.email_membre));
        row.appendChild(createCell(formatDate(item.date_inscription)));
        row.appendChild(createCell(formatPayment(item.paiement_inscription)));
        row.appendChild(createCell(`${parseFloat(item.prix_inscription).toFixed(2)} €`));

        const actionCell = document.createElement("td");
        actionCell.className = "actions-cell";

        const removeButton = document.createElement("button");
        removeButton.className = "btn-transparent btn-red action-btn";
        removeButton.innerHTML = '<img src="../ressources/delete.svg" alt="Supprimer">Retirer';
        removeButton.onclick = () => confirmDelete(item);

        actionCell.appendChild(removeButton);
        row.appendChild(actionCell);

        tbody.appendChild(row);
    });
}

async function refreshData() {
    showLoader();

    try {
        defaultData = await requestGET("inscription");
        loadData();
    } catch (error) {
        toast(error.message, true);
    } finally {
        hideLoader();
    }
}

async function deleteInscription(item) {
    showLoader();

    try {
        await requestDELETE(`inscription&id_membre=${item.id_membre}&id_evenement=${item.id_evenement}`);
        defaultData = defaultData.filter(
            (current) => !(current.id_membre === item.id_membre && current.id_evenement === item.id_evenement)
        );
        loadData();
        toast("Inscription retiree avec succes.");
    } catch (error) {
        toast(error.message, true);
    } finally {
        hideLoader();
    }
}

function confirmDelete(item) {
    swal({
        title: "Retirer cette inscription ?",
        text: "Le membre sera desinscrit de l evenement sans remboursement automatique.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            deleteInscription(item);
        }
    });
}

eventSearch.addEventListener("input", loadData);
userSearch.addEventListener("input", loadData);

refreshData();
