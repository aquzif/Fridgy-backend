import { createTheme } from '@mui/material/styles';

const theme = createTheme({
    palette: {
        mode: 'light',
        primary: {
            light: '#7dd3fc',
            main: '#0ea5e9',
            dark: '#0369a1',
            contrastText: '#ffffff',
        },
        secondary: {
            light: '#a78bfa',
            main: '#8b5cf6',
            dark: '#6d28d9',
            contrastText: '#ffffff',
        },
        background: {
            default: '#f4f7fb',
            paper: '#ffffff',
        },
        text: {
            primary: '#0f172a',
            secondary: '#475569',
        }
    },
    shape: {
        borderRadius: 14,
    },
    typography: {
        fontFamily: '"Inter", "Montserrat", "Segoe UI", sans-serif',
    },
    components: {
        MuiPaper: {
            styleOverrides: {
                root: {
                    borderRadius: 16,
                    border: '1px solid #e2e8f0',
                    boxShadow: '0 10px 30px rgba(15, 23, 42, 0.08)',
                    backgroundImage: 'none',
                },
            },
        },
        MuiCard: {
            styleOverrides: {
                root: {
                    borderRadius: 16,
                    border: '1px solid #e2e8f0',
                    boxShadow: '0 10px 30px rgba(15, 23, 42, 0.08)',
                },
            },
        },
        MuiDialog: {
            styleOverrides: {
                paper: {
                    borderRadius: 18,
                },
            },
        },
        MuiButton: {
            defaultProps: {
                disableElevation: true,
            },
            styleOverrides: {
                root: {
                    borderRadius: 12,
                    textTransform: 'none',
                    fontWeight: 600,
                    paddingInline: '16px',
                },
            },
        },
        MuiIconButton: {
            styleOverrides: {
                root: {
                    borderRadius: 12,
                },
            },
        },
        MuiChip: {
            styleOverrides: {
                root: {
                    borderRadius: 10,
                },
            },
        },
        MuiTextField: {
            defaultProps: {
                variant: 'outlined',
            },
        },
        MuiOutlinedInput: {
            styleOverrides: {
                root: {
                    borderRadius: 12,
                    backgroundColor: '#ffffff',
                    '& .MuiOutlinedInput-notchedOutline': {
                        borderColor: '#dbe3ef',
                    },
                    '&:hover .MuiOutlinedInput-notchedOutline': {
                        borderColor: '#a5b4fc',
                    },
                    '&.Mui-focused .MuiOutlinedInput-notchedOutline': {
                        borderWidth: '1px',
                        borderColor: '#0ea5e9',
                    },
                },
            },
        },
        MuiTabs: {
            styleOverrides: {
                indicator: {
                    height: 3,
                    borderRadius: 999,
                },
            },
        },
    },
});


export default theme;
