import { refreshNavbar } from "./navbar.js";
import { requestGET, requestDELETE, requestPOST } from "./ajax.js";
import { showLoader, hideLoader } from "./loader.js";
import { toast } from "./toaster.js";
import { showPropertieSkeleton, hidePropertieSkeleton } from "./propertieskeleton.js";
import { getFileBucketUrl, openFileDialog } from "./files.js";

showPropertieSkeleton();

const download_btn = document.getElementById("download_btn");
const delete_btn = document.getElementById("delete_btn");
const new_btn = document.getElementById("new_btn");
const pdf_preview = document.getElementById("pdf_preview");

async function fetchData() {
    let meetings = [];

    try {
        meetings = await requestGET("meeting");
    } catch (error) {
        toast(error.message, true);
    }

    return meetings.map((meeting) => ({
        label: meeting.date_reunion.split(" ")[0],
        id: meeting.id_reunion,
    }));
}

async function deleteReunion(id_reunion) {
    showLoader();

    try {
        await requestDELETE(`meeting&id=${id_reunion}`);
        refreshNavbar(fetchData, selectReunion);
        toast("Reunion supprimee avec succes.");
    } catch (error) {
        toast(error.message, true);
    } finally {
        hideLoader();
    }
}

async function selectReunion(id_reunion) {
    showPropertieSkeleton();
    showLoader();

    try {
        const meeting = await requestGET(`meeting&id=${id_reunion}`);
        const url = getFileBucketUrl(meeting.fichier_reunion);

        pdf_preview.src = url;
        download_btn.onclick = () => {
            window.open(url, "_blank");
        };

        delete_btn.onclick = () => {
            swal({
                title: "Etes vous sur ?",
                text: "Cette action est definitive",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    deleteReunion(id_reunion);
                }
            });
        };
    } catch (error) {
        toast(error.message, true);
    } finally {
        hideLoader();
        hidePropertieSkeleton();
    }
}

new_btn.onclick = async () => {
    try {
        const file = await openFileDialog("application/pdf");

        showLoader();

        const form_data = new FormData();
        form_data.append("file", file);
        form_data.append("date", new Date().toISOString().split("T")[0]);

        const { id_reunion } = await requestPOST("meeting", form_data);
        refreshNavbar(fetchData, selectReunion, id_reunion);
        toast("Fichier uploade avec succes");
    } catch (error) {
        toast(error.message, true);
    } finally {
        hideLoader();
    }
};

refreshNavbar(fetchData, selectReunion);
