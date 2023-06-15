import { BrowserRouter, Routes } from "react-router-dom";
import { Route } from "react-router";

import Header from "./composants/Header";
import Home from "./pages/home/Home";
import Login from "./pages/Login";

import "./styles/composants.scss";
import "./styles/index.scss";
import "./styles/pages.scss";

function App() {
  return (
    <div className="App">
      <BrowserRouter>
        <Header />
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/login" element={<Login />} />
        </Routes>
      </BrowserRouter>
    </div>
  );
}

export default App;
