import React from "react";
import styled from "styled-components";

const HorizontalMargin = styled(({ margin, ...rest }) => <span {...rest} />)`
  display: flex;
  min-width: ${({ margin }) =>
    typeof margin === "string" ? margin : `${margin}px`};
`;

const VerticalMargin = styled(({ margin, ...rest }) => <span {...rest} />)`
  display: flex;
  min-height: ${({ margin }) =>
    typeof margin === "string" ? margin : `${margin}px`};
`;

function Marginer(props) {
  const { direction } = props;

  if (direction === "horizontal") return <HorizontalMargin {...props} />;
  else {
    return <VerticalMargin {...props} />;
  }
}

export { Marginer };
