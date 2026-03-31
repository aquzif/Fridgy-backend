import { createTheme } from '@mui/material/styles';

const theme = createTheme({
    palette: {
        mode: 'light',
        primary: {
            light: '#8EA3FF',
            main: '#5B6CFF',
            dark: '#4452CC',
            contrastText: '#FFFFFF',
        },
        secondary: {
            light: '#7EE5D3',
            main: '#3BC6AD',
            dark: '#2D9B87',
            contrastText: '#FFFFFF',
        },
        background: {
            default: '#F4F7FF',
            paper: '#FFFFFF',
        },
        text: {
            primary: '#1E2447',
            secondary: '#5E668E',
        },
    },
    shape: {
        borderRadius: 14,
    },
    typography: {
        fontFamily: 'Montserrat, sans-serif',
        button: {
            fontWeight: 700,
            textTransform: 'none',
        },
    },
    components: {
        MuiPaper: {
            styleOverrides: {
                root: {
                    borderRadius: 16,
                    boxShadow: '0 8px 30px rgba(30, 36, 71, 0.08)',
                    backgroundImage: 'none',
                },
            },
        },
        MuiButton: {
            styleOverrides: {
                root: {
                    borderRadius: 12,
                    padding: '10px 18px',
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
                    backgroundColor: '#FFFFFF',
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
    },
});


export default theme;
