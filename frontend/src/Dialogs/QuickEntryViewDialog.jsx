import {useEffect, useState} from "react";
import {Button, Dialog, DialogActions, DialogContent, DialogTitle, Typography} from "@mui/material";
import CalendarEntriesAPI from "@/API/CalendarEntriesAPI";

const QuickEntryViewDialog = ({open, onClose, id}) => {
    const [entry,setEntry] = useState(null);

    const load = async () => {
        const {data} = await CalendarEntriesAPI.get(id);
        setEntry(data.data);
    }

    useEffect(() => { if(open && id) load(); }, [open,id]);

    const item = entry?.calendar_entry_quick_entries?.[0];

    return (
        <Dialog open={open} onClose={onClose} fullWidth maxWidth="xs">
            <DialogTitle>Szczegóły</DialogTitle>
            <DialogContent>
                <Typography variant="h6">{item?.name}</Typography>
                <Typography variant="h6">Kalorie: {item?.calories}</Typography>
            </DialogContent>
            <DialogActions>
                <Button onClick={onClose}>Zamknij</Button>
            </DialogActions>
        </Dialog>
    );
}

export default QuickEntryViewDialog;
