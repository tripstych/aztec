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
  `}
`;

const WindshieldReplacement = () => {
  return (
    <PageContainer>
      <NavBar />
      <TopSection />
      <Marginer direction="vertical" margin="2em" />
      <AboutSection />
      <Footer />
    </PageContainer>
  );
};

export default WindshieldReplacement;
