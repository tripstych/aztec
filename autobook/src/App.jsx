import React from "react";
import styled from "styled-components";
import "./App.css";
import tw from "twin.macro";
import Home from "./app/containers/Home";
import Quote from "./app/containers/Quote";
import MobileService from "./app/containers/MobileService";
import WindshieldReplacement from "./app/containers/WindshieldReplacement";
import RockChipRepair from "./app/containers/RockChipRepair";
import PrivacyPolicy from "./app/containers/PrivacyPolicy";
import Contact from "./app/containers/Contact";
import { BrowserRouter, Routes, Route } from "react-router-dom";

const AppContainer = styled.div`
  ${tw`
    w-full
    h-full
    flex
    flex-col
    font-montserrat
  `}
`;

const App = () => {
  return (
    <AppContainer>
      <BrowserRouter
        future={{
          v7_startTransition: true,
          v7_relativeSplatPath: true,
        }}
      >
        <Routes>
          <Route path="/">
            <Route index element={<Home />} />
            <Route path="quote">
              <Route index element={<Quote />} />
            </Route>
            <Route path="windshield-replacement">
              <Route index element={<WindshieldReplacement />} />
            </Route>
            <Route path="mobile-service">
              <Route index element={<MobileService />} />
            </Route>
            <Route path="rock-chip-repair">
              <Route index element={<RockChipRepair />} />
            </Route>
            <Route path="privacy-policy">
              <Route index element={<PrivacyPolicy />} />
            </Route>
            <Route path="contact">
              <Route index element={<Contact />} />
            </Route>
          </Route>
        </Routes>
      </BrowserRouter>
    </AppContainer>
  );
};

export default App;
