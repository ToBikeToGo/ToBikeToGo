import { createTheme } from '@mui/material/styles';

const theme = createTheme({
  palette: {
    text: {
      primary: '#051c2c',
      secondary: '#051c2c',
    },
    primary: {
      main: 'rgba(75,158,46,0.25)',
    },
    secondary: {
      main: 'rgba(75,158,46,0.25)',
    },
    black: {
      main: '#051c2c',
    },
    white: {
      main: '#fff',
    },
    red: {
      main: '#f17878',
    },
    background: {
      default: '#fff',
    },
    success: {
      main: '#53dc75',
    },
  },
  overrides: {
    MuiTextField: {
      root: {
        backgroundColor: '#fff',
      },
    },
  },
  components: {
    MuiMenu: {
      styleOverrides: {
        paper: {
          boxShadow: 'none',
        },
      },
    },
  },
  typography: {
    h1: {
      fontFamily: 'Young Serif, serif',
      fontSize: '2.5rem',
      fontWeight: 700,
      lineHeight: 1.2,
    },
    h2: {
      fontSize: '2rem',
      fontWeight: 900,
      margin: '1rem 0',
      color: 'black',
      fontFamily: 'Young Serif, serif',
    },
    h3: {
      fontSize: '1.5rem',
      fontWeight: 700,
      margin: '1rem 0',
      color: 'black',
      fontFamily: 'Young Serif, serif',
    },
    allVariants: {
      color: '#051c2c',
    },
  },
});

export default theme;
