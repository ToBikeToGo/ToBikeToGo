import { useState } from 'react'
import reactLogo from './assets/react.svg'
import viteLogo from '/vite.svg'
import './App.css'
import {BrowserRouter, Route, Routes} from "react-router-dom";
import Login from "./pages/Login/Login.jsx";
import Navbar from "./components/Navbar/Navbar.jsx";
import {ThemeProvider} from "@mui/material/styles";

import theme from './theme'
import styled from "styled-components";


const StyledApp = styled.div`
    background-color: ${theme.palette.background.default};
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    `
function App() {

  return (
    <>
        <ThemeProvider theme={theme}>
            <StyledApp>
        <Navbar/>
        <BrowserRouter>
            <Routes>
                <Route exact path='/login' element={<Login/>} />
            </Routes>
        </BrowserRouter>
            </StyledApp>
        </ThemeProvider>
    </>
  )
}

export default App
