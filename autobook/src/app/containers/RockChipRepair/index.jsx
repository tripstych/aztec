import React from "react";
import styled from "styled-components";
import tw from "twin.macro";
import NavBar from "../../components/navbar";
import { Footer } from "../../components/footer";
import { Marginer } from "../../components/marginer";
import TopSection from "./topSection";
import AboutSection from "./aboutSection";

const PageContainer = styled.div`
  ${tw`
  flex
  flex-col
  w-full
  h-full
  items-center
  overflow-x-hidden
  `}
`;

const RockChipRepair = () => {
  return (
    <PageContainer>
      <NavBar />
      <TopSection />
      <Marginer direction="vertical" margin="2em" />
      <AboutSection />
      <Marginer direction="vertical" margin="2em" />
      <Footer />
    </PageContainer>
  );
};

export default RockChipRepair;
