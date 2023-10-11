import { createTheme, ThemeProvider } from '@mui/material/styles';

const theme = createTheme({
    palette: {
        text: {
            primary: '#051c2c',
        },
        primary: {
            main: '#051c2c',
        },
        secondary: {
            main: '#ff5722',
        },
        background: {
            default: '#fff',
        }
    },
    typography: {
        fontFamily: 'Roboto, sans-serif',
        h1: {
            fontSize: '2.5rem',
            fontWeight: 700,
            lineHeight: 1.2,
        },
        h2: {
            fontSize: '2rem',
            fontWeight: 900,
        },s
        allVariants: {
            color: '#051c2c',
        }
    },
});

export default theme;