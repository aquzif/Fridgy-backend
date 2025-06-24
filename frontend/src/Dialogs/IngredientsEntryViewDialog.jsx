import {useEffect, useState} from "react";
import {Button, Dialog, DialogActions, DialogContent, DialogTitle, Typography} from "@mui/material";
import CalendarEntriesAPI from "@/API/CalendarEntriesAPI";

const IngredientsEntryViewDialog = ({open, onClose, id}) => {
    const [entry,setEntry] = useState(null);

    const load = async () => {
        const {data} = await CalendarEntriesAPI.get(id);
        setEntry(data.data);
    }

    useEffect(() => { if(open && id) load(); }, [open,id]);

    return (
        <Dialog open={open} onClose={onClose} fullWidth maxWidth="sm">
            <DialogTitle>Szczegóły</DialogTitle>
            <DialogContent>
                <Typography variant="h6">Składniki:</Typography>
                <ul style={{marginLeft:20}}>
                    {entry?.calendar_entry_ingredients?.map(it => (
                        <li key={it.id}>{it.ingredient.name} - {it.amount}{it.unit.name} ({Math.round(it.calories)} kcal)</li>
                    ))}
                </ul>
                <Typography variant="h6">Podsumowanie: {Math.round(entry?.calories||0)} kcal</Typography>
            </DialogContent>
            <DialogActions>
                <Button onClick={onClose}>Zamknij</Button>
            </DialogActions>
        </Dialog>
    );
}

export default IngredientsEntryViewDialog;
